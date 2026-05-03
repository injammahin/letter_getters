<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChildHabitatPurchase extends Model
{
    protected $fillable = [
        'user_id',
        'theme_id',
        'asset_id',
        'price_paid',
        'purchased_at',
    ];

    protected $casts = [
        'price_paid' => 'integer',
        'purchased_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function theme(): BelongsTo
    {
        return $this->belongsTo(HabitantTheme::class, 'theme_id');
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(HabitantAsset::class, 'asset_id');
    }
}