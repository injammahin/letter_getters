<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ChildRegistrationApprovalMail;
use App\Models\Interest;
use App\Models\ParentApproval;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        $interests = Interest::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('auth.register', compact('interests'));
    }

    public function store(Request $request): RedirectResponse
    {
        $role = $request->input('role', 'child');

        $rules = [
            'role' => ['required', Rule::in(['child', 'adult'])],
            'display_name' => ['required', 'string', 'max:120'],
            'username' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:users,username'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],
            'age_or_grade' => ['required', 'string', 'max:50'],
            'city' => ['required', 'string', 'max:120'],
            'state' => ['required', 'string', 'max:120'],
            'agreement' => ['accepted'],
        ];

        if ($role === 'child') {
            $rules['parent_email'] = ['required', 'email', 'max:255', 'different:email'];
        }

        if ($role === 'adult') {
            $rules['short_bio'] = ['nullable', 'string', 'max:800'];
            $rules['interests'] = ['required', 'array', 'min:1'];
            $rules['interests.*'] = ['exists:interests,id'];
        }

        $data = $request->validate($rules);

        $user = User::create([
            'name' => $data['display_name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'account_status' => $data['role'] === 'child' ? 'pending_parent_approval' : 'active',
            'parent_email' => $data['role'] === 'child' ? $data['parent_email'] : null,
        ]);

        Profile::create([
            'user_id' => $user->id,
            'display_name' => $data['display_name'],
            'age_or_grade' => $data['age_or_grade'],
            'city' => $data['city'],
            'state' => $data['state'],
            'short_bio' => $data['role'] === 'adult' ? ($data['short_bio'] ?? null) : null,
        ]);

        if ($data['role'] === 'adult' && ! empty($data['interests'])) {
            $user->interests()->sync($data['interests']);
        }

        if ($data['role'] === 'child') {
            $approval = ParentApproval::create([
                'child_user_id' => $user->id,
                'parent_email' => $data['parent_email'],
                'token' => Str::random(64),
                'status' => 'pending',
                'expires_at' => now()->addDays(7),
            ]);

            Mail::to($approval->parent_email)->send(new ChildRegistrationApprovalMail($approval));

            return redirect()
                ->route('register.pending')
                ->with('success', 'Registration submitted. A parent approval email has been sent.')
                ->with('parent_email', $approval->parent_email)
                ->with('child_name', $user->name);
        }

        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('adult.dashboard');
    }
}