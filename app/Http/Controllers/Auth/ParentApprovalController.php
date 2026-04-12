<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ParentApproval;
use App\Models\ParentChildLink;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ParentApprovalController extends Controller
{
    public function show(string $token): View
    {
        $approval = ParentApproval::with('child.profile')
            ->where('token', $token)
            ->where('status', 'pending')
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->firstOrFail();

        return view('auth.parent-approve', compact('approval'));
    }

    public function store(Request $request, string $token): RedirectResponse
    {
        $approval = ParentApproval::with('child.profile')
            ->where('token', $token)
            ->where('status', 'pending')
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->firstOrFail();

        $data = $request->validate([
            'parent_name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', 'min:8'],
            'confirm_approval' => ['accepted'],
        ]);

        DB::transaction(function () use ($approval, $data) {
            $existingUser = User::where('email', $approval->parent_email)->first();

            if ($existingUser && !in_array($existingUser->role, ['parent'])) {
                throw ValidationException::withMessages([
                    'parent_name' => 'This email is already used by another account type.',
                ]);
            }

            $parent = User::updateOrCreate(
                ['email' => $approval->parent_email],
                [
                    'name' => $data['parent_name'],
                    'role' => 'parent',
                    'account_status' => 'active',
                    'password' => Hash::make($data['password']),
                    'email_verified_at' => now(),
                ]
            );

            ParentChildLink::updateOrCreate([
                'parent_user_id' => $parent->id,
                'child_user_id' => $approval->child_user_id,
            ]);

            $child = $approval->child;
            $child->account_status = 'active';
            $child->save();

            $approval->status = 'approved';
            $approval->approved_at = now();
            $approval->save();
        });

        return redirect()
            ->route('login')
            ->with('success', 'Child registration approved successfully. You can now sign in as parent.');
    }
}