<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $data['email'])
            ->where('role', 'adult')
            ->first();

        if (! $user) {
            return back()
                ->withErrors([
                    'email' => 'No adult account was found with this email address.',
                ])
                ->withInput();
        }

        if ($user->hasVerifiedEmail()) {
            return back()->with('status', 'This email is already verified. You can sign in now.');
        }

        $user->sendEmailVerificationNotification();

        return back()
            ->with('status', 'A new verification email has been sent.')
            ->with('verification_email', $user->email);
    }
}