<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChildMatchRequest extends Model
{
    protected $fillable = [
        'requester_user_id',
        'target_user_id',
        'status',
        'shared_interest_count',
        'score',
        'admin_notes',
        'reviewed_by',
        'reviewed_at',
        'accepted_by',
        'accepted_at',
        'declined_by',
        'declined_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
        'accepted_at' => 'datetime',
        'declined_at' => 'datetime',
        'shared_interest_count' => 'integer',
        'score' => 'integer',
    ];

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_user_id');
    }

    public function target(): BelongsTo
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }

    public function acceptedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'accepted_by');
    }

    public function declinedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'declined_by');
    }
}