<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChildHabitat extends Model
{
    protected $fillable = [
        'user_id',
        'theme_id',
        'active_background_asset_id',
        'active_avatar_asset_id',
        'hunger',
        'happiness',
        'last_fed_at',
        'last_played_at',
        'guide_completed_at',
    ];

    protected $casts = [
        'hunger' => 'integer',
        'happiness' => 'integer',
        'last_fed_at' => 'datetime',
        'last_played_at' => 'datetime',
        'guide_completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function theme(): BelongsTo
    {
        return $this->belongsTo(HabitantTheme::class, 'theme_id');
    }

    public function activeBackground(): BelongsTo
    {
        return $this->belongsTo(HabitantAsset::class, 'active_background_asset_id');
    }

    public function activeAvatar(): BelongsTo
    {
        return $this->belongsTo(HabitantAsset::class, 'active_avatar_asset_id');
    }

    public function layouts(): HasMany
    {
        return $this->hasMany(ChildHabitatLayout::class, 'child_habitat_id');
    }

    public function refreshHungerState(): void
    {
        if (! $this->last_fed_at) {
            $this->hunger = min($this->hunger, 35);
            $this->save();

            return;
        }

        $hoursPassed = $this->last_fed_at->diffInHours(now());

        /*
         * Every hour hunger reduces by around 4 points.
         * After 24 hours, hunger becomes very low and avatar becomes sad.
         */
        $newHunger = max(0, 100 - ($hoursPassed * 4));

        if ($newHunger !== $this->hunger) {
            $this->hunger = $newHunger;

            if ($newHunger < 35) {
                $this->happiness = max(20, $this->happiness - 5);
            }

            $this->save();
        }
    }

    public function isSad(): bool
    {
        return $this->hunger < 35;
    }
}