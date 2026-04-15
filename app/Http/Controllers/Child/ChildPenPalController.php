<?php

namespace App\Http\Controllers\Child;

use App\Http\Controllers\Controller;
use App\Models\ChildConversation;
use App\Models\ChildMatch;
use App\Models\ChildMatchRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ChildPenPalController extends Controller
{
    public function index(): View
    {
        $this->ensureChild();

        $child = auth()->user()->loadMissing([
            'profile.avatarLibrary',
            'interests',
        ]);

        $pendingRequests = ChildMatchRequest::query()
            ->with([
                'target.profile.avatarLibrary',
                'target.interests',
            ])
            ->where('requester_user_id', $child->id)
            ->where('status', 'pending_admin_approval')
            ->latest()
            ->get()
            ->map(function (ChildMatchRequest $matchRequest) use ($child) {
                $stats = $this->buildMatchStats($child, $matchRequest->target);
                $matchRequest->shared_interest_names = $stats['shared_interest_names'];
                return $matchRequest;
            });

        $approvedMatchRows = ChildMatch::query()
            ->with([
                'userOne.profile.avatarLibrary',
                'userOne.interests',
                'userTwo.profile.avatarLibrary',
                'userTwo.interests',
            ])
            ->where('status', 'active')
            ->where(function ($query) use ($child) {
                $query->where('user_one_id', $child->id)
                    ->orWhere('user_two_id', $child->id);
            })
            ->latest('approved_at')
            ->get();

        $approvedMatches = $approvedMatchRows->map(function (ChildMatch $match) use ($child) {
            $penPal = $match->user_one_id === $child->id ? $match->userTwo : $match->userOne;
            $stats = $this->buildMatchStats($child, $penPal);

            $match->pen_pal = $penPal;
            $match->shared_interest_names = $stats['shared_interest_names'];

            return $match;
        });

        $excludedIds = collect()
            ->merge($pendingRequests->pluck('target_user_id'))
            ->merge(
                ChildMatchRequest::query()
                    ->where('target_user_id', $child->id)
                    ->where('status', 'pending_admin_approval')
                    ->pluck('requester_user_id')
            )
            ->merge(
                $approvedMatches->map(function (ChildMatch $match) {
                    return $match->pen_pal?->id;
                })->filter()
            )
            ->unique()
            ->values();

        $suggestions = $this->eligibleChildrenQuery()
            ->where('id', '!=', $child->id)
            ->get()
            ->reject(function (User $candidate) use ($excludedIds) {
                return $excludedIds->contains($candidate->id);
            })
            ->map(function (User $candidate) use ($child) {
                $stats = $this->buildMatchStats($child, $candidate);

                if ($stats['shared_count'] < 1) {
                    return null;
                }

                $candidate->match_score = $stats['score'];
                $candidate->shared_interest_count = $stats['shared_count'];
                $candidate->shared_interest_names = $stats['shared_interest_names'];

                return $candidate;
            })
            ->filter()
            ->sortByDesc('match_score')
            ->take(12)
            ->values();

        return view('child.pen-pals', [
            'child' => $child,
            'suggestions' => $suggestions,
            'pendingRequests' => $pendingRequests,
            'approvedMatches' => $approvedMatches,
        ]);
    }

    public function sendRequest(User $targetUser): RedirectResponse
    {
        $this->ensureChild();

        $child = auth()->user()->loadMissing(['profile.avatarLibrary', 'interests']);
        $targetUser->loadMissing(['profile.avatarLibrary', 'interests']);

        if ($child->id === $targetUser->id) {
            return back()->with('error', 'You cannot send a match request to yourself.');
        }

        if ($targetUser->role !== 'child' || $targetUser->account_status !== 'active') {
            return back()->with('error', 'This child account is not available for matching.');
        }

        if (! $this->isEligibleChild($targetUser)) {
            return back()->with('error', 'This child profile is not ready for matching yet.');
        }

        if ($this->hasExistingRelation($child->id, $targetUser->id)) {
            return back()->with('error', 'A match request or match already exists with this child.');
        }

        $stats = $this->buildMatchStats($child, $targetUser);

        if ($stats['shared_count'] < 1) {
            return back()->with('error', 'This child does not currently qualify as a suggested match.');
        }

        ChildMatchRequest::create([
            'requester_user_id' => $child->id,
            'target_user_id' => $targetUser->id,
            'status' => 'pending_admin_approval',
            'shared_interest_count' => $stats['shared_count'],
            'score' => $stats['score'],
        ]);

        return back()->with('success', 'Match request sent successfully. It is now waiting for admin approval.');
    }

    protected function ensureChild(): void
    {
        abort_unless(auth()->check() && auth()->user()->role === 'child', 403);
    }

    protected function eligibleChildrenQuery(): Builder
    {
        return User::query()
            ->with([
                'profile.avatarLibrary',
                'interests',
            ])
            ->where('role', 'child')
            ->where('account_status', 'active')
            ->whereHas('interests')
            ->whereHas('profile', function ($query) {
                $query->whereNotNull('profile_completed_at')
                    ->where('display_name', '!=', '')
                    ->where('age_or_grade', '!=', '')
                    ->where('city', '!=', '')
                    ->where('state', '!=', '')
                    ->where('short_bio', '!=', '')
                    ->where(function ($avatarQuery) {
                        $avatarQuery
                            ->where(function ($uploadQuery) {
                                $uploadQuery->where('avatar_type', 'upload')
                                    ->whereNotNull('avatar');
                            })
                            ->orWhere(function ($libraryQuery) {
                                $libraryQuery->where('avatar_type', 'library')
                                    ->whereNotNull('avatar_library_id');
                            });
                    });
            });
    }

    protected function isEligibleChild(User $user): bool
    {
        return $this->eligibleChildrenQuery()
            ->where('id', $user->id)
            ->exists();
    }

    protected function hasExistingRelation(int $firstUserId, int $secondUserId): bool
    {
        $activeMatchExists = ChildMatch::query()
            ->where('status', 'active')
            ->where(function ($query) use ($firstUserId, $secondUserId) {
                $query->where(function ($subQuery) use ($firstUserId, $secondUserId) {
                    $subQuery->where('user_one_id', $firstUserId)
                        ->where('user_two_id', $secondUserId);
                })->orWhere(function ($subQuery) use ($firstUserId, $secondUserId) {
                    $subQuery->where('user_one_id', $secondUserId)
                        ->where('user_two_id', $firstUserId);
                });
            })
            ->exists();

        $pendingOrApprovedRequestExists = ChildMatchRequest::query()
            ->whereIn('status', ['pending_admin_approval', 'approved'])
            ->where(function ($query) use ($firstUserId, $secondUserId) {
                $query->where(function ($subQuery) use ($firstUserId, $secondUserId) {
                    $subQuery->where('requester_user_id', $firstUserId)
                        ->where('target_user_id', $secondUserId);
                })->orWhere(function ($subQuery) use ($firstUserId, $secondUserId) {
                    $subQuery->where('requester_user_id', $secondUserId)
                        ->where('target_user_id', $firstUserId);
                });
            })
            ->exists();

        return $activeMatchExists || $pendingOrApprovedRequestExists;
    }

    protected function buildMatchStats(User $child, User $candidate): array
    {
        $child->loadMissing('interests', 'profile');
        $candidate->loadMissing('interests', 'profile');

        $childInterestIds = $child->interests->pluck('id')->all();
        $sharedInterests = $candidate->interests
            ->whereIn('id', $childInterestIds)
            ->values();

        $score = $sharedInterests->count() * 10;

        $childAge = $this->extractNumber($child->profile?->age_or_grade);
        $candidateAge = $this->extractNumber($candidate->profile?->age_or_grade);

        if (! is_null($childAge) && ! is_null($candidateAge)) {
            $difference = abs($childAge - $candidateAge);

            if ($difference <= 1) {
                $score += 6;
            } elseif ($difference <= 2) {
                $score += 3;
            }
        }

        if (
            filled($child->profile?->city) &&
            filled($candidate->profile?->city) &&
            strcasecmp(trim($child->profile->city), trim($candidate->profile->city)) === 0
        ) {
            $score += 2;
        }

        if (
            filled($child->profile?->state) &&
            filled($candidate->profile?->state) &&
            strcasecmp(trim($child->profile->state), trim($candidate->profile->state)) === 0
        ) {
            $score += 1;
        }

        return [
            'score' => $score,
            'shared_count' => $sharedInterests->count(),
            'shared_interest_names' => $sharedInterests->pluck('name')->values(),
        ];
    }

    protected function extractNumber(?string $value): ?int
    {
        if (! $value) {
            return null;
        }

        if (preg_match('/(\d+)/', $value, $matches)) {
            return (int) $matches[1];
        }

        return null;
    }
}