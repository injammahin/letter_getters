<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChildAvatar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChildAvatarController extends Controller
{
    public function index()
    {
        $avatars = ChildAvatar::latest()->get();

        return view('admin.child-avatars.index', compact('avatars'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $path = $request->file('image')->store('avatars/library', 'public');

        ChildAvatar::create([
            'name' => 'Avatar '.now()->format('YmdHis'),
            'image_path' => $path,
            'is_active' => true,
            'sort_order' => 0,
        ]);

        return redirect()
            ->route('admin.child-avatars.index')
            ->with('success', 'Avatar uploaded successfully.');
    }

    public function update(Request $request, ChildAvatar $child_avatar)
    {
        $data = $request->validate([
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            if ($child_avatar->image_path) {
                Storage::disk('public')->delete($child_avatar->image_path);
            }

            $child_avatar->image_path = $request->file('image')->store('avatars/library', 'public');
            $child_avatar->save();
        }

        return redirect()
            ->route('admin.child-avatars.index')
            ->with('success', 'Avatar updated successfully.');
    }

    public function destroy(ChildAvatar $child_avatar)
    {
        if ($child_avatar->image_path) {
            Storage::disk('public')->delete($child_avatar->image_path);
        }

        $child_avatar->delete();

        return redirect()
            ->route('admin.child-avatars.index')
            ->with('success', 'Avatar deleted successfully.');
    }
}