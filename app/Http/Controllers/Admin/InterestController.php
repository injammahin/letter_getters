<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Interest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InterestController extends Controller
{
    public function index()
    {
        $interests = Interest::orderBy('sort_order')->orderBy('name')->get();

        return view('admin.interests.index', compact('interests'));
    }

    public function create()
    {
        return view('admin.interests.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:interests,name'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        Interest::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.interests.index')
            ->with('success', 'Interest created successfully.');
    }

    public function edit(Interest $interest)
    {
        return view('admin.interests.edit', compact('interest'));
    }

    public function update(Request $request, Interest $interest)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:interests,name,' . $interest->id],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $interest->update([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.interests.index')
            ->with('success', 'Interest updated successfully.');
    }

    public function destroy(Interest $interest)
    {
        $interest->delete();

        return redirect()->route('admin.interests.index')
            ->with('success', 'Interest deleted successfully.');
    }
}