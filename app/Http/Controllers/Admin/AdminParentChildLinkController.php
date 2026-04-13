<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParentChildLink;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminParentChildLinkController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->get('search'));

        $links = ParentChildLink::query()
            ->with([
                'parent.profile',
                'child.profile',
            ])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->whereHas('parent', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%")
                                ->orWhere('username', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        })
                        ->orWhereHas('child', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%")
                                ->orWhere('username', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $totalLinks = ParentChildLink::count();

        return view('admin.parents-children.index', compact('links', 'search', 'totalLinks'));
    }

    public function destroy(ParentChildLink $parentChildLink): RedirectResponse
    {
        $parentChildLink->delete();

        return back()->with('success', 'Parent-child link removed successfully.');
    }
}