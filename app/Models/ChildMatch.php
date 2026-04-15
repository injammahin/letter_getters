<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChildMatch extends Model
{
    protected $fillable = [
        'user_one_id',
        'user_two_id',
        'approved_request_id',
        'approved_by',
        'status',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function userOne()
    {
        return $this->belongsTo(User::class, 'user_one_id');
    }

    public function userTwo()
    {
        return $this->belongsTo(User::class, 'user_two_id');
    }

    public function approvedRequest()
    {
        return $this->belongsTo(ChildMatchRequest::class, 'approved_request_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}