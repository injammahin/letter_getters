<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    protected $fillable = [
        'user_id',
        'ticket_number',
        'full_name',
        'email',
        'subject',
        'category',
        'priority',
        'message',
        'status',
        'last_replied_at',
        'solved_at',
    ];

    protected $casts = [
        'last_replied_at' => 'datetime',
        'solved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(SupportTicketReply::class)->latest();
    }
}