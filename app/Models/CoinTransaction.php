<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoinTransaction extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'reward_key',
        'label',
        'amount',
        'balance_before',
        'balance_after',
        'meta',
        'animation_seen_at',
    ];

    protected $casts = [
        'meta' => 'array',
        'animation_seen_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}