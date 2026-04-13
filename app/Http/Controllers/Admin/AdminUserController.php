<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::query()
            ->with('profile')
            ->where('account_status', 'active')
            ->whereNotIn('role', ['admin', 'superadmin'])
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'pageTitle' => 'Active Users',
            'pageSubtitle' => 'All active non-admin accounts',
            'roleLabel' => 'All Active Users',
            'roleKey' => 'all',
        ]);
    }

    public function children(): View
    {
        return $this->roleListing('child', 'Child Users', 'All active child accounts');
    }

    public function parents(): View
    {
        return $this->roleListing('parent', 'Parent Users', 'All active parent accounts');
    }

    public function adults(): View
    {
        return $this->roleListing('adult', 'Adult Users', 'All active adult accounts');
    }

    protected function roleListing(string $role, string $pageTitle, string $pageSubtitle): View
    {
        $users = User::query()
            ->with('profile')
            ->where('account_status', 'active')
            ->where('role', $role)
            ->whereNotIn('role', ['admin', 'superadmin'])
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'pageTitle' => $pageTitle,
            'pageSubtitle' => $pageSubtitle,
            'roleLabel' => ucfirst($role),
            'roleKey' => $role,
        ]);
    }

    public function suspend(User $user): RedirectResponse
    {
        if (in_array($user->role, ['admin', 'superadmin'], true)) {
            return back()->with('error', 'Admin accounts cannot be suspended from here.');
        }

        $user->update([
            'account_status' => 'suspended',
        ]);

        return back()->with('success', 'User suspended successfully.');
    }

    public function suspendedAccounts(Request $request): View
    {
        $role = $request->string('role')->toString();

        $allowedRoles = ['child', 'parent', 'adult'];

        $users = User::query()
            ->with('profile')
            ->where('account_status', 'suspended')
            ->whereNotIn('role', ['admin', 'superadmin'])
            ->when(in_array($role, $allowedRoles, true), function ($query) use ($role) {
                $query->where('role', $role);
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.moderation.suspended-accounts', [
            'users' => $users,
            'currentRole' => in_array($role, $allowedRoles, true) ? $role : 'all',
        ]);
    }

    public function activate(User $user): RedirectResponse
    {
        if (in_array($user->role, ['admin', 'superadmin'], true)) {
            return back()->with('error', 'Admin accounts cannot be changed from here.');
        }

        $user->update([
            'account_status' => 'active',
        ]);

        return back()->with('success', 'User activated successfully.');
    }
}