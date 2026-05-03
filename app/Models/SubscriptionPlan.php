<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'badge',
        'short_description',
        'description',
        'features',
        'price',
        'currency',
        'billing_interval',
        'stripe_price_id',
        'trial_days',
        'sort_order',
        'is_active',
        'is_featured',
    ];

    protected $casts = [
        'features' => 'array',
        'price' => 'decimal:2',
        'trial_days' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function subscriptions()
    {
        return $this->hasMany(UserSubscription::class);
    }

    public function getFormattedPriceAttribute(): string
    {
        if ((float) $this->price <= 0) {
            return 'Free';
        }

        return strtoupper($this->currency) . ' ' . number_format((float) $this->price, 2);
    }

    public function getBillingLabelAttribute(): string
    {
        return match ($this->billing_interval) {
            'monthly' => 'Monthly',
            'yearly' => 'Yearly',
            'lifetime' => 'Lifetime',
            'one_time' => 'One Time',
            default => ucfirst(str_replace('_', ' ', $this->billing_interval)),
        };
    }
}