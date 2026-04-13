<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $login = $this->input('login');
        $password = $this->input('password');

        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $user = User::where($field, $login)->first();

        if ($user && Hash::check($password, $user->password)) {
            if ($user->account_status === 'pending_parent_approval') {
                throw ValidationException::withMessages([
                    'login' => 'Your account is waiting for parent approval. You cannot sign in yet.',
                ]);
            }

            if ($user->account_status !== 'active') {
                throw ValidationException::withMessages([
                    'login' => 'Your account is not active yet. Please contact support.',
                ]);
            }

            if ($user->role === 'adult' && is_null($user->email_verified_at)) {
                throw ValidationException::withMessages([
                    'login' => 'Please verify your email address before signing in.',
                ]);
            }
        }

        if (! Auth::attempt([
            $field => $login,
            'password' => $password,
            'account_status' => 'active',
        ], $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'login' => 'These credentials do not match our records.',
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'login' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    public function throttleKey(): string
    {
        return Str::transliterate(
            Str::lower($this->string('login')).'|'.$this->ip()
        );
    }
}