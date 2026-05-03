<?php

namespace App\Http\Controllers\Child;

use App\Http\Controllers\Controller;
use App\Models\ChildConversation;
use App\Models\ChildHabitat;
use App\Models\ChildMatch;
use App\Models\ChildMatchRequest;
use App\Models\HabitantTheme;
use App\Models\User;
use App\Notifications\PenPalRequestAcceptedNotification;
use App\Notifications\PenPalRequestReceivedNotification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
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

        $incomingRequests = ChildMatchRequest::query()
            ->with([
                'requester.profile.avatarLibrary',
                'requester.interests',
            ])
            ->where('target_user_id', $child->id)
            ->where('status', 'pending_receiver_approval')
            ->latest()
            ->get()
            ->map(function (ChildMatchRequest $matchRequest) use ($child) {
                $stats = $this->buildMatchStats($child, $matchRequest->requester);

                $matchRequest->shared_interest_names = $stats['shared_interest_names'];

                return $matchRequest;
            });

        $pendingRequests = ChildMatchRequest::query()
            ->with([
                'target.profile.avatarLibrary',
                'target.interests',
            ])
            ->where('requester_user_id', $child->id)
            ->where('status', 'pending_receiver_approval')
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
            $penPal = (int) $match->user_one_id === (int) $child->id
                ? $match->userTwo
                : $match->userOne;

            $stats = $this->buildMatchStats($child, $penPal);

            $match->pen_pal = $penPal;
            $match->shared_interest_names = $stats['shared_interest_names'];

            return $match;
        });

        $excludedIds = collect()
            ->merge($pendingRequests->pluck('target_user_id'))
            ->merge($incomingRequests->pluck('requester_user_id'))
            ->merge(
                $approvedMatches
                    ->map(fn (ChildMatch $match) => $match->pen_pal?->id)
                    ->filter()
            )
            ->unique()
            ->values();

        if ($approvedMatches->isNotEmpty()) {
            $suggestions = collect();
        } else {
            $childrenAlreadyMatchedIds = ChildMatch::query()
                ->where('status', 'active')
                ->get()
                ->flatMap(function (ChildMatch $match) {
                    return [
                        $match->user_one_id,
                        $match->user_two_id,
                    ];
                })
                ->unique()
                ->values();

            $suggestions = $this->eligibleChildrenQuery()
                ->where('id', '!=', $child->id)
                ->get()
                ->reject(function (User $candidate) use ($excludedIds, $childrenAlreadyMatchedIds) {
                    return $excludedIds->contains($candidate->id)
                        || $childrenAlreadyMatchedIds->contains($candidate->id);
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
        }

        return view('child.pen-pals', [
            'child' => $child,
            'suggestions' => $suggestions,
            'incomingRequests' => $incomingRequests,
            'pendingRequests' => $pendingRequests,
            'approvedMatches' => $approvedMatches,
        ]);
    }

    public function sendRequest(User $targetUser): RedirectResponse
    {
        $this->ensureChild();

        $child = auth()->user()->loadMissing([
            'profile.avatarLibrary',
            'interests',
        ]);

        $targetUser->loadMissing([
            'profile.avatarLibrary',
            'interests',
        ]);

        if ((int) $child->id === (int) $targetUser->id) {
            return back()->with('error', 'You cannot send a match request to yourself.');
        }

        if ($targetUser->role !== 'child' || $targetUser->account_status !== 'active') {
            return back()->with('error', 'This child account is not available for matching.');
        }

        if (! $this->isEligibleChild($child)) {
            return back()->with('error', 'Your profile is not ready for matching yet.');
        }

        if (! $this->isEligibleChild($targetUser)) {
            return back()->with('error', 'This child profile is not ready for matching yet.');
        }

        if ($this->childHasActiveMatch($child->id)) {
            return back()->with('error', 'You already have an active pen pal match.');
        }

        if ($this->childHasActiveMatch($targetUser->id)) {
            return back()->with('error', 'This child already has an active pen pal match.');
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
            'status' => 'pending_receiver_approval',
            'shared_interest_count' => $stats['shared_count'],
            'score' => $stats['score'],
        ]);

        $targetUser->notify(
            new PenPalRequestReceivedNotification($child)
        );

        return back()->with('success', 'Match request sent successfully. Your pen pal can now accept or decline it.');
    }

    public function acceptRequest(ChildMatchRequest $childMatchRequest): RedirectResponse
    {
        $this->ensureChild();

        $child = auth()->user();

        if ((int) $childMatchRequest->target_user_id !== (int) $child->id) {
            abort(403);
        }

        if ($childMatchRequest->status !== 'pending_receiver_approval') {
            return back()->with('error', 'This match request is no longer pending.');
        }

        $match = DB::transaction(function () use ($childMatchRequest, $child) {
            $lockedRequest = ChildMatchRequest::query()
                ->whereKey($childMatchRequest->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedRequest->status !== 'pending_receiver_approval') {
                return null;
            }

            $userIds = [
                (int) $lockedRequest->requester_user_id,
                (int) $lockedRequest->target_user_id,
            ];

            sort($userIds);

            User::query()
                ->whereIn('id', $userIds)
                ->lockForUpdate()
                ->get();

            $activeMatchExists = ChildMatch::query()
                ->where('status', 'active')
                ->where(function ($query) use ($userIds) {
                    $query->whereIn('user_one_id', $userIds)
                        ->orWhereIn('user_two_id', $userIds);
                })
                ->exists();

            if ($activeMatchExists) {
                return false;
            }

            $match = ChildMatch::query()
                ->where('user_one_id', $userIds[0])
                ->where('user_two_id', $userIds[1])
                ->first();

            if (! $match) {
                $match = ChildMatch::create([
                    'user_one_id' => $userIds[0],
                    'user_two_id' => $userIds[1],
                    'approved_request_id' => $lockedRequest->id,
                    'approved_by' => $child->id,
                    'status' => 'active',
                    'approved_at' => now(),
                ]);
            } else {
                $match->update([
                    'approved_request_id' => $lockedRequest->id,
                    'approved_by' => $child->id,
                    'status' => 'active',
                    'approved_at' => now(),
                ]);
            }

            $lockedRequest->update([
                'status' => 'accepted',
                'accepted_by' => $child->id,
                'accepted_at' => now(),
                'reviewed_by' => $child->id,
                'reviewed_at' => now(),
            ]);

            ChildConversation::firstOrCreate([
                'child_match_id' => $match->id,
            ]);

            return $match;
        });

        if ($match === false) {
            return back()->with('error', 'This match cannot be accepted because one child already has an active match.');
        }

        if ($match === null) {
            return back()->with('error', 'This match request is no longer pending.');
        }

        $childMatchRequest->loadMissing('requester');

        $childMatchRequest->requester?->notify(
            new PenPalRequestAcceptedNotification($child)
        );

        return back()->with('success', 'Match accepted. You can now visit your pen pal’s habitant.');
    }

    public function declineRequest(ChildMatchRequest $childMatchRequest): RedirectResponse
    {
        $this->ensureChild();

        $child = auth()->user();

        if ((int) $childMatchRequest->target_user_id !== (int) $child->id) {
            abort(403);
        }

        if ($childMatchRequest->status !== 'pending_receiver_approval') {
            return back()->with('error', 'This match request is no longer pending.');
        }

        $childMatchRequest->update([
            'status' => 'declined',
            'declined_by' => $child->id,
            'declined_at' => now(),
        ]);

        return back()->with('success', 'Match request declined.');
    }

    public function cancelRequest(ChildMatchRequest $childMatchRequest): RedirectResponse
    {
        $this->ensureChild();

        $child = auth()->user();

        if ((int) $childMatchRequest->requester_user_id !== (int) $child->id) {
            abort(403);
        }

        if ($childMatchRequest->status !== 'pending_receiver_approval') {
            return back()->with('error', 'This match request can no longer be cancelled.');
        }

        $childMatchRequest->update([
            'status' => 'cancelled',
        ]);

        return back()->with('success', 'Match request cancelled.');
    }

    public function visitHabitant(User $penPal): View
    {
        $this->ensureChild();

        $child = auth()->user();

        abort_unless($penPal->role === 'child', 404);

        $hasActiveMatch = ChildMatch::query()
            ->where('status', 'active')
            ->where(function ($query) use ($child, $penPal) {
                $query->where(function ($q) use ($child, $penPal) {
                    $q->where('user_one_id', $child->id)
                        ->where('user_two_id', $penPal->id);
                })->orWhere(function ($q) use ($child, $penPal) {
                    $q->where('user_one_id', $penPal->id)
                        ->where('user_two_id', $child->id);
                });
            })
            ->exists();

        abort_unless($hasActiveMatch, 403);

        $penPal->loadMissing([
            'profile.avatarLibrary',
            'interests',
        ]);

        $themes = HabitantTheme::query()
            ->with([
                'activeAssets' => function ($query) {
                    $query->orderBy('sort_order')
                        ->orderBy('type')
                        ->orderBy('name');
                },
            ])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        /*
         * One child can have multiple habitat rows for different themes.
         * We select the best usable one, not just the latest empty one.
         */
        $habitats = ChildHabitat::query()
            ->with(['layouts'])
            ->where('user_id', $penPal->id)
            ->get();

        $selectedTheme = null;
        $habitat = null;

        if (request()->filled('theme')) {
            $selectedTheme = $themes->firstWhere('slug', request('theme'));

            if ($selectedTheme) {
                $habitat = $habitats->firstWhere('theme_id', $selectedTheme->id);
            }
        }

        if (! $habitat) {
            $habitat = $habitats
                ->sortByDesc(function (ChildHabitat $item) {
                    $score = 0;

                    if ($item->active_background_asset_id) {
                        $score += 1000000;
                    }

                    if ($item->active_avatar_asset_id) {
                        $score += 500000;
                    }

                    $score += $item->layouts->count() * 10000;
                    $score += optional($item->updated_at)->timestamp ?? 0;

                    return $score;
                })
                ->first();
        }

        if (! $selectedTheme && $habitat?->theme_id) {
            $selectedTheme = $themes->firstWhere('id', (int) $habitat->theme_id);
        }

        $selectedTheme ??= $themes->first();

        $layoutRows = collect();
        $ownedAssetIds = collect();

        $activeBackgroundId = null;
        $activeAvatarId = null;

        if ($habitat) {
            $activeBackgroundId = $habitat->active_background_asset_id;
            $activeAvatarId = $habitat->active_avatar_asset_id;

            /*
             * Correct relationship:
             * child_habitat_layouts.child_habitat_id = child_habitats.id
             * child_habitat_layouts.asset_id = habitant_assets.id
             */
            $layoutRows = $habitat->layouts
                ->keyBy('asset_id');

            /*
             * Your project may have a purchase table.
             * Use DB/Scheme instead of a model, so it will not crash if no model exists.
             */
            $purchaseTable = $this->firstExistingTable([
                'child_habitat_purchases',
                'child_habitant_purchases',
                'habitant_purchases',
                'user_habitat_assets',
                'user_habitant_assets',
                'child_habitat_assets',
                'child_habitant_assets',
            ]);

            if ($purchaseTable) {
                $purchaseUserColumn = $this->firstExistingColumn($purchaseTable, [
                    'user_id',
                    'child_user_id',
                ]);

                $purchaseThemeColumn = $this->firstExistingColumn($purchaseTable, [
                    'theme_id',
                    'habitant_theme_id',
                ]);

                $purchaseAssetColumn = $this->firstExistingColumn($purchaseTable, [
                    'asset_id',
                    'habitant_asset_id',
                    'habitat_asset_id',
                ]);

                if ($purchaseUserColumn && $purchaseAssetColumn) {
                    $purchaseQuery = DB::table($purchaseTable)
                        ->where($purchaseUserColumn, $penPal->id);

                    if ($purchaseThemeColumn && $habitat->theme_id) {
                        $purchaseQuery->where($purchaseThemeColumn, $habitat->theme_id);
                    }

                    $ownedAssetIds = $purchaseQuery
                        ->pluck($purchaseAssetColumn)
                        ->map(fn ($id) => (int) $id);
                }
            }
        }

        /*
         * If active background/avatar are missing, fallback to purchased or placed assets
         * from the selected theme only.
         */
        if ($selectedTheme) {
            $themeAssets = $selectedTheme->activeAssets;

            if (! $activeBackgroundId) {
                $activeBackgroundId = optional(
                    $themeAssets
                        ->where('type', 'background')
                        ->first(function ($asset) use ($ownedAssetIds, $layoutRows) {
                            return $ownedAssetIds->contains((int) $asset->id)
                                || $layoutRows->has($asset->id);
                        })
                )->id;
            }

            if (! $activeAvatarId) {
                $activeAvatarId = optional(
                    $themeAssets
                        ->where('type', 'avatar')
                        ->first(function ($asset) use ($ownedAssetIds, $layoutRows) {
                            return $ownedAssetIds->contains((int) $asset->id)
                                || $layoutRows->has($asset->id);
                        })
                )->id;
            }
        }

        /*
         * Visitor page should show:
         * active background,
         * active avatar,
         * every saved placed layout item.
         */
        $ownedAssetIds = $ownedAssetIds
            ->merge($layoutRows->keys())
            ->merge([
                $activeBackgroundId,
                $activeAvatarId,
            ])
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        $isSad = $habitat ? $habitat->isSad() : false;

        return view('child.pen-pal-habitant', [
            'penPal' => $penPal,
            'themes' => $themes,
            'selectedTheme' => $selectedTheme,
            'habitat' => $habitat,
            'ownedAssetIds' => $ownedAssetIds,
            'layoutRows' => $layoutRows,
            'isSad' => $isSad,
            'activeBackgroundId' => $activeBackgroundId,
            'activeAvatarId' => $activeAvatarId,
        ]);
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

    protected function childHasActiveMatch(int $userId): bool
    {
        return ChildMatch::query()
            ->where('status', 'active')
            ->where(function ($query) use ($userId) {
                $query->where('user_one_id', $userId)
                    ->orWhere('user_two_id', $userId);
            })
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

        $requestExists = ChildMatchRequest::query()
            ->whereIn('status', [
                'pending_receiver_approval',
                'accepted',
                'pending_admin_approval',
                'approved',
            ])
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

        return $activeMatchExists || $requestExists;
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

    protected function firstExistingTable(array $tables): ?string
    {
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                return $table;
            }
        }

        return null;
    }

    protected function firstExistingColumn(string $table, array $columns): ?string
    {
        if (! Schema::hasTable($table)) {
            return null;
        }

        foreach ($columns as $column) {
            if (Schema::hasColumn($table, $column)) {
                return $column;
            }
        }

        return null;
    }
}