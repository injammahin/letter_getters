<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
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

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user();

        return redirect()->intended($this->redirectToByRole($user));
    }

    protected function redirectToByRole($user): string
    {
        return match ($user->role) {
            'superadmin', 'admin' => route('admin.dashboard'),
            'adult' => route('adult.dashboard'),
            'parent' => route('parent.dashboard'),
            'child' => $user->hasCompletedChildProfile()
                ? route('child.dashboard')
                : route('child.profile.complete'),
            default => route('home'),
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