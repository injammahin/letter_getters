<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_user_id');
    }

    public function target()
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}