<?php

namespace App\Services;

use App\Models\CoinTransaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ChildCoinService
{
    public function awardOnce(
        User $user,
        string $rewardKey,
        int $amount,
        string $label,
        array $meta = []
    ): ?CoinTransaction {
        return DB::transaction(function () use ($user, $rewardKey, $amount, $label, $meta) {
            $lockedUser = User::query()->lockForUpdate()->findOrFail($user->id);

            $alreadyRewarded = CoinTransaction::query()
                ->where('user_id', $lockedUser->id)
                ->where('reward_key', $rewardKey)
                ->exists();

            if ($alreadyRewarded) {
                return null;
            }

            $before = (int) $lockedUser->coin_balance;
            $after = $before + $amount;

            $lockedUser->forceFill([
                'coin_balance' => $after,
            ])->save();

            return CoinTransaction::create([
                'user_id' => $lockedUser->id,
                'type' => 'credit',
                'reward_key' => $rewardKey,
                'label' => $label,
                'amount' => $amount,
                'balance_before' => $before,
                'balance_after' => $after,
                'meta' => $meta,
                'animation_seen_at' => null,
            ]);
        });
    }

    public function consumePendingAnimations(User $user): ?array
    {
        return DB::transaction(function () use ($user) {
            $pending = CoinTransaction::query()
                ->where('user_id', $user->id)
                ->where('type', 'credit')
                ->whereNull('animation_seen_at')
                ->orderBy('id')
                ->lockForUpdate()
                ->get();

            if ($pending->isEmpty()) {
                return null;
            }

            $payload = [
                'amount' => (int) $pending->sum('amount'),
                'previous_balance' => (int) $pending->first()->balance_before,
                'new_balance' => (int) $pending->last()->balance_after,
                'labels' => $pending->pluck('label')->values()->all(),
            ];

            CoinTransaction::query()
                ->whereIn('id', $pending->pluck('id'))
                ->update([
                    'animation_seen_at' => now(),
                ]);

            return $payload;
        });
    }
}