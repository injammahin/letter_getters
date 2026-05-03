<?php

namespace App\Http\Controllers\Child;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Stripe\StripeClient;

class ChildPlanController extends Controller
{
    public function index(): View
    {
        $this->ensureChild();

        $child = Auth::user();

        $plans = SubscriptionPlan::query()
            ->where('is_active', true)
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->orderBy('price')
            ->orderBy('name')
            ->get();

        $currentSubscription = $child->activePremiumSubscription();

        return view('child.plans', compact('plans', 'child', 'currentSubscription'));
    }

    public function checkout(SubscriptionPlan $subscriptionPlan): RedirectResponse
    {
        $this->ensureChild();

        $child = Auth::user();

        if (! $subscriptionPlan->is_active) {
            return back()->with('error', 'This plan is not available right now.');
        }

        if (! $subscriptionPlan->stripe_price_id) {
            return back()->with('error', 'Stripe Price ID is missing for this plan.');
        }

        $stripe = new StripeClient(config('services.stripe.secret'));

        $mode = in_array($subscriptionPlan->billing_interval, ['monthly', 'yearly'])
            ? 'subscription'
            : 'payment';

        $session = $stripe->checkout->sessions->create([
            'mode' => $mode,
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price' => $subscriptionPlan->stripe_price_id,
                    'quantity' => 1,
                ],
            ],
            'success_url' => route('child.plans.checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('child.plans.checkout.cancel'),
            'customer_email' => $child->email,
            'metadata' => [
                'user_id' => $child->id,
                'plan_id' => $subscriptionPlan->id,
            ],
        ]);

        return redirect($session->url);
    }

    public function success(Request $request): RedirectResponse
    {
        $this->ensureChild();

        $child = Auth::user();

        $request->validate([
            'session_id' => ['required', 'string'],
        ]);

        $stripe = new StripeClient(config('services.stripe.secret'));

        $session = $stripe->checkout->sessions->retrieve($request->session_id, []);

        if (! $session || empty($session->metadata->plan_id)) {
            return redirect()
                ->route('child.plans')
                ->with('error', 'Payment session could not be verified.');
        }

        $plan = SubscriptionPlan::find($session->metadata->plan_id);

        if (! $plan) {
            return redirect()
                ->route('child.plans')
                ->with('error', 'Plan not found.');
        }

        $alreadySaved = UserSubscription::where('stripe_checkout_session_id', $session->id)->exists();

        if ($alreadySaved) {
            return redirect()
                ->route('child.plans')
                ->with('success', 'Plan purchased successfully.');
        }

        $status = 'active';
        $startsAt = now();
        $endsAt = null;
        $stripeSubscriptionId = null;

        if (! empty($session->subscription)) {
            $stripeSubscription = $stripe->subscriptions->retrieve($session->subscription, []);

            $stripeSubscriptionId = $stripeSubscription->id;

            if (! empty($stripeSubscription->current_period_end)) {
                $endsAt = Carbon::createFromTimestamp($stripeSubscription->current_period_end);
            }

            if (! in_array($stripeSubscription->status, ['active', 'trialing'])) {
                $status = 'pending';
            }
        }

        DB::transaction(function () use (
            $child,
            $plan,
            $session,
            $status,
            $startsAt,
            $endsAt,
            $stripeSubscriptionId
        ) {
            UserSubscription::where('user_id', $child->id)
                ->where('status', 'active')
                ->update([
                    'status' => 'replaced',
                    'cancelled_at' => now(),
                ]);

            UserSubscription::create([
                'user_id' => $child->id,
                'subscription_plan_id' => $plan->id,
                'stripe_checkout_session_id' => $session->id,
                'stripe_customer_id' => $session->customer ?: null,
                'stripe_subscription_id' => $stripeSubscriptionId,
                'stripe_price_id' => $plan->stripe_price_id,
                'amount' => $session->amount_total ? ($session->amount_total / 100) : $plan->price,
                'currency' => strtoupper($session->currency ?? $plan->currency),
                'billing_interval' => $plan->billing_interval,
                'status' => $status,
                'starts_at' => $startsAt,
                'ends_at' => $endsAt,
                'purchased_at' => now(),
                'payload' => $session->toArray(),
            ]);
        });

        return redirect()
            ->route('child.plans')
            ->with('success', 'Plan purchased successfully. Your premium plan is now active.');
    }

    public function cancel(): RedirectResponse
    {
        return redirect()
            ->route('child.plans')
            ->with('error', 'Payment was cancelled.');
    }

    protected function ensureChild(): void
    {
        abort_unless(Auth::check() && Auth::user()->role === 'child', 403);
    }
}