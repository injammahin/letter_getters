<?php

namespace App\Http\Controllers\Child;

use App\Http\Controllers\Controller;
use App\Models\ChildAvatar;
use App\Models\Interest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ChildProfileController extends Controller
{
    public function edit(): View
    {
        $user = auth()->user();

        $interests = Interest::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $avatars = ChildAvatar::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('child.profile-complete', compact('user', 'interests', 'avatars'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $data = $request->validate([
            'display_name' => ['required', 'string', 'max:120'],
            'age_or_grade' => ['required', 'string', 'max:50'],
            'city' => ['required', 'string', 'max:120'],
            'state' => ['required', 'string', 'max:120'],
            'short_bio' => ['nullable', 'string', 'max:500'],
            'favorite_color' => ['nullable', 'string', 'max:30'],
            'avatar_mode' => ['required', 'in:library,upload'],
            'avatar_library_id' => ['nullable', 'exists:child_avatars,id'],
            'avatar_upload' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'interests' => ['required', 'array', 'min:1'],
            'interests.*' => ['exists:interests,id'],
        ]);

        if ($data['avatar_mode'] === 'library' && empty($data['avatar_library_id'])) {
            return back()->withErrors([
                'avatar_library_id' => 'Please choose an avatar.',
            ])->withInput();
        }

        if ($data['avatar_mode'] === 'upload' && ! $request->hasFile('avatar_upload')) {
            return back()->withErrors([
                'avatar_upload' => 'Please upload an avatar image.',
            ])->withInput();
        }

        $profile = $user->profile()->firstOrNew([]);

        $profile->display_name = $data['display_name'];
        $profile->age_or_grade = $data['age_or_grade'];
        $profile->city = $data['city'];
        $profile->state = $data['state'];
        $profile->short_bio = $data['short_bio'] ?? null;
        $profile->favorite_color = $data['favorite_color'] ?? null;

        if ($data['avatar_mode'] === 'library') {
            if ($profile->avatar_type === 'upload' && filled($profile->avatar)) {
                Storage::disk('public')->delete($profile->avatar);
            }

            $profile->avatar_type = 'library';
            $profile->avatar_library_id = (int) $data['avatar_library_id'];
            $profile->avatar = null;
        } else {
            if ($request->hasFile('avatar_upload')) {
                if ($profile->avatar_type === 'upload' && filled($profile->avatar)) {
                    Storage::disk('public')->delete($profile->avatar);
                }

                $path = $request->file('avatar_upload')->store('avatars/children', 'public');

                $profile->avatar_type = 'upload';
                $profile->avatar_library_id = null;
                $profile->avatar = $path;
            }
        }

        $profile->profile_completed_at = now();
        $profile->save();

        $user->name = $data['display_name'];
        $user->save();

        $user->interests()->sync($data['interests']);

        return redirect()
            ->route('child.dashboard')
            ->with('profile_completed', true);
    }
}