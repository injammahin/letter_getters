<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SubscriptionPlanController extends Controller
{
    public function index(Request $request): View
    {
        $plans = SubscriptionPlan::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = trim($request->search);

                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%")
                        ->orWhere('short_description', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->orderBy('price')
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        $totalPlans = SubscriptionPlan::count();
        $activePlans = SubscriptionPlan::where('is_active', true)->count();
        $featuredPlans = SubscriptionPlan::where('is_featured', true)->count();

        return view('admin.subscription-plans.index', compact(
            'plans',
            'totalPlans',
            'activePlans',
            'featuredPlans'
        ));
    }

    public function create(): View
    {
        $subscriptionPlan = new SubscriptionPlan([
            'currency' => 'USD',
            'billing_interval' => 'monthly',
            'price' => 0,
            'trial_days' => 0,
            'sort_order' => 0,
            'is_active' => true,
            'is_featured' => false,
            'features' => [],
        ]);

        return view('admin.subscription-plans.create', compact('subscriptionPlan'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        $slugBase = $data['slug'] ?: $data['name'];

        SubscriptionPlan::create([
            'name' => $data['name'],
            'slug' => $this->makeUniqueSlug($slugBase),
            'badge' => $data['badge'] ?? null,
            'short_description' => $data['short_description'] ?? null,
            'description' => $data['description'] ?? null,
            'features' => $this->featuresFromText($data['features_text'] ?? null),
            'price' => $data['price'],
            'currency' => strtoupper($data['currency']),
            'billing_interval' => $data['billing_interval'],
            'stripe_price_id' => $data['stripe_price_id'] ?? null,
            'trial_days' => $data['trial_days'] ?? 0,
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
            'is_featured' => $request->boolean('is_featured'),
        ]);

        return redirect()
            ->route('admin.subscription-plans.index')
            ->with('success', 'Subscription plan created successfully.');
    }

    public function edit(SubscriptionPlan $subscriptionPlan): View
    {
        return view('admin.subscription-plans.edit', compact('subscriptionPlan'));
    }

    public function update(Request $request, SubscriptionPlan $subscriptionPlan): RedirectResponse
    {
        $data = $this->validatedData($request);

        $slugBase = $data['slug'] ?: $data['name'];

        $subscriptionPlan->update([
            'name' => $data['name'],
            'slug' => $this->makeUniqueSlug($slugBase, $subscriptionPlan->id),
            'badge' => $data['badge'] ?? null,
            'short_description' => $data['short_description'] ?? null,
            'description' => $data['description'] ?? null,
            'features' => $this->featuresFromText($data['features_text'] ?? null),
            'price' => $data['price'],
            'currency' => strtoupper($data['currency']),
            'billing_interval' => $data['billing_interval'],
            'stripe_price_id' => $data['stripe_price_id'] ?? null,
            'trial_days' => $data['trial_days'] ?? 0,
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
            'is_featured' => $request->boolean('is_featured'),
        ]);

        return redirect()
            ->route('admin.subscription-plans.index')
            ->with('success', 'Subscription plan updated successfully.');
    }

    public function destroy(SubscriptionPlan $subscriptionPlan): RedirectResponse
    {
        $subscriptionPlan->delete();

        return redirect()
            ->route('admin.subscription-plans.index')
            ->with('success', 'Subscription plan deleted successfully.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:140', 'alpha_dash'],
            'badge' => ['nullable', 'string', 'max:60'],
            'short_description' => ['nullable', 'string', 'max:180'],
            'description' => ['nullable', 'string', 'max:2000'],
            'features_text' => ['nullable', 'string', 'max:4000'],
            'price' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'currency' => ['required', 'string', 'max:10'],
            'billing_interval' => ['required', 'string', 'in:monthly,yearly,lifetime,one_time'],
            'stripe_price_id' => ['nullable', 'string', 'max:255'],
            'trial_days' => ['nullable', 'integer', 'min:0', 'max:365'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
        ]);
    }

    private function featuresFromText(?string $text): array
    {
        return collect(preg_split('/\r\n|\r|\n/', (string) $text))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->all();
    }

    private function makeUniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($value);

        if ($baseSlug === '') {
            $baseSlug = 'subscription-plan';
        }

        $slug = $baseSlug;
        $counter = 2;

        while (
            SubscriptionPlan::query()
                ->where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}