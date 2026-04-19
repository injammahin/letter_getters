<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ParentApproval;
use App\Models\ParentChildLink;
use App\Models\User;
use App\Services\ChildCoinService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

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

    public function store(
        Request $request,
        string $token,
        ChildCoinService $coinService
    ): RedirectResponse {
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

        DB::transaction(function () use ($approval, $data, $coinService) {
            $existingUser = User::where('email', $approval->parent_email)
                ->lockForUpdate()
                ->first();

            if ($existingUser && $existingUser->role !== 'parent') {
                throw ValidationException::withMessages([
                    'parent_name' => 'This email is already used by another account type.',
                ]);
            }

            if ($existingUser) {
                $parent = $existingUser;

                $parent->update([
                    'name' => $data['parent_name'],
                    'role' => 'parent',
                    'account_status' => 'active',
                    'password' => Hash::make($data['password']),
                    'email_verified_at' => $parent->email_verified_at ?? now(),
                ]);
            } else {
                $parent = User::create([
                    'name' => $data['parent_name'],
                    'username' => $this->generateUniqueParentUsername($data['parent_name']),
                    'email' => $approval->parent_email,
                    'role' => 'parent',
                    'account_status' => 'active',
                    'password' => Hash::make($data['password']),
                    'email_verified_at' => now(),
                ]);
            }

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

            $coinService->awardOnce(
                $child->fresh(),
                'child_registration_parent_approved',
                40,
                'Parent approval reward'
            );
        });

        return redirect()
            ->route('login')
            ->with('success', 'Child registration approved successfully. The child received 40 coins and the parent account is now active.');
    }

    protected function generateUniqueParentUsername(string $name): string
    {
        $base = Str::slug($name, '') ?: 'parent';
        $base = Str::lower(substr($base, 0, 20));

        do {
            $username = $base . Str::lower(Str::random(5));
        } while (User::where('username', $username)->exists());

        return $username;
    }
}