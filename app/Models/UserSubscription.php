<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class UserSubscription extends Model
{
    protected $fillable = [
        'user_id',
        'subscription_plan_id',
        'stripe_checkout_session_id',
        'stripe_customer_id',
        'stripe_subscription_id',
        'stripe_price_id',
        'amount',
        'currency',
        'billing_interval',
        'status',
        'starts_at',
        'ends_at',
        'purchased_at',
        'cancelled_at',
        'payload',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'purchased_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'payload' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    public function isActive(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        if ($this->ends_at && $this->ends_at->isPast()) {
            return false;
        }

        return true;
    }
}