<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParentApproval extends Model
{
    protected $fillable = [
        'child_user_id',
        'parent_email',
        'token',
        'status',
        'expires_at',
        'approved_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function child()
    {
        return $this->belongsTo(User::class, 'child_user_id');
    }
}