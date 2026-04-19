<?php

namespace App\Http\Controllers\Child;

use App\Http\Controllers\Controller;
use App\Models\ChildAvatar;
use App\Models\Interest;
use App\Models\Profile;
use App\Services\ChildCoinService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ChildProfileController extends Controller
{
    public function edit(): View
    {
        $child = auth()->user()->loadMissing([
            'profile.avatarLibrary',
            'interests',
        ]);

        $avatars = ChildAvatar::query()
            ->where('is_active', true)
            ->latest()
            ->get();

        $interests = Interest::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('child.profile-complete', compact('child', 'avatars', 'interests'));
    }

    public function update(Request $request, ChildCoinService $coinService): RedirectResponse
    {
        $child = auth()->user()->loadMissing([
            'profile.avatarLibrary',
            'interests',
        ]);

        $saveSection = $request->input('save_section', 'all');

        abort_unless(in_array($saveSection, ['details', 'interests', 'avatar', 'all'], true), 403);

        $rules = [];

        if (in_array($saveSection, ['details', 'all'], true)) {
            $rules = array_merge($rules, [
                'display_name' => ['nullable', 'string', 'max:120'],
                'age_or_grade' => ['nullable', 'string', 'max:50'],
                'city' => ['nullable', 'string', 'max:120'],
                'state' => ['nullable', 'string', 'max:120'],
                'short_bio' => ['nullable', 'string', 'max:800'],
            ]);
        }

        if (in_array($saveSection, ['interests', 'all'], true)) {
            $rules = array_merge($rules, [
                'interests' => ['nullable', 'array'],
                'interests.*' => ['exists:interests,id'],
            ]);
        }

        if (in_array($saveSection, ['avatar', 'all'], true)) {
            $rules = array_merge($rules, [
                'avatar_type' => ['nullable', Rule::in(['library', 'upload'])],
                'avatar_library_id' => ['nullable', 'exists:child_avatars,id'],
                'avatar' => ['nullable', 'image', 'max:4096'],
            ]);
        }

        $data = $request->validate($rules);

        $profile = $child->profile ?: new Profile([
            'user_id' => $child->id,
        ]);

        if (in_array($saveSection, ['details', 'all'], true)) {
            $child->update([
                'name' => $request->filled('display_name')
                    ? $request->input('display_name')
                    : ($profile->display_name ?: $child->name),
            ]);

            $profile->display_name = $request->input('display_name', $profile->display_name);
            $profile->age_or_grade = $request->input('age_or_grade', $profile->age_or_grade);
            $profile->city = $request->input('city', $profile->city);
            $profile->state = $request->input('state', $profile->state);
            $profile->short_bio = $request->input('short_bio', $profile->short_bio);
        }

        if (in_array($saveSection, ['interests', 'all'], true) && $request->has('interests')) {
            $child->interests()->sync($data['interests'] ?? []);
        }

        if (in_array($saveSection, ['avatar', 'all'], true)) {
            $avatarType = $request->input('avatar_type', $profile->avatar_type);

            if ($avatarType === 'library' && $request->filled('avatar_library_id')) {
                if ($profile->avatar_type === 'upload' && filled($profile->avatar)) {
                    Storage::disk('public')->delete($profile->avatar);
                }

                $profile->avatar_type = 'library';
                $profile->avatar_library_id = $request->input('avatar_library_id');
                $profile->avatar = null;
            }

            if ($avatarType === 'upload' && $request->hasFile('avatar')) {
                if ($profile->avatar_type === 'upload' && filled($profile->avatar)) {
                    Storage::disk('public')->delete($profile->avatar);
                }

                $profile->avatar_type = 'upload';
                $profile->avatar_library_id = null;
                $profile->avatar = $request->file('avatar')->store('child/avatars/uploaded', 'public');
            }
        }

        $isComplete = $this->hasAvatar($profile)
            && $child->interests()->exists()
            && $this->hasCompleteDetails($profile);

        $profile->profile_completed_at = $isComplete ? ($profile->profile_completed_at ?: now()) : null;
        $profile->save();

        if ($this->hasAvatar($profile)) {
            $coinService->awardOnce(
                $child->fresh(),
                'child_profile_avatar_reward',
                20,
                'Avatar reward'
            );
        }

        if ($child->fresh()->interests()->exists()) {
            $coinService->awardOnce(
                $child->fresh(),
                'child_profile_interest_reward',
                20,
                'Interest reward'
            );
        }

        if ($this->hasCompleteDetails($profile)) {
            $coinService->awardOnce(
                $child->fresh(),
                'child_profile_details_reward',
                20,
                'Profile details reward'
            );
        }

        $coinAnimation = $coinService->consumePendingAnimations($child->fresh());

        $message = match ($saveSection) {
            'details' => 'Basic information saved successfully.',
            'interests' => 'Interests saved successfully.',
            'avatar' => 'Avatar saved successfully.',
            default => 'Profile saved successfully.',
        };

        $redirect = $saveSection === 'all'
            ? redirect()->route('child.dashboard')->with('success', $message)
            : redirect()->route('child.profile.complete')->with('success', $message);

        if ($coinAnimation) {
            $redirect->with('coin_reward_animation', $coinAnimation);
        }

        return $redirect;
    }

    protected function hasAvatar(Profile $profile): bool
    {
        return ($profile->avatar_type === 'upload' && filled($profile->avatar))
            || ($profile->avatar_type === 'library' && !empty($profile->avatar_library_id));
    }

    protected function hasCompleteDetails(Profile $profile): bool
    {
        return filled($profile->display_name)
            && filled($profile->age_or_grade)
            && filled($profile->city)
            && filled($profile->state)
            && filled($profile->short_bio);
    }
}