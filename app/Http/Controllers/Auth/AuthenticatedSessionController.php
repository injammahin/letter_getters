<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\ChildCoinService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request, ChildCoinService $coinService): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user();

        if ($user->role === 'child') {
            $coinAnimation = $coinService->consumePendingAnimations($user);

            if ($coinAnimation) {
                $request->session()->flash('coin_reward_animation', $coinAnimation);
            }
        }

        return match ($user->role) {
            'superadmin', 'admin' => redirect()->intended(route('admin.dashboard')),
            'child' => $user->hasCompletedChildProfile()
                ? redirect()->intended(route('child.dashboard'))
                : redirect()->intended(route('child.profile.complete')),
            'adult' => redirect()->intended(route('adult.dashboard')),
            'parent' => redirect()->intended(route('parent.dashboard')),
            default => redirect()->intended(route('dashboard')),
        };
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}