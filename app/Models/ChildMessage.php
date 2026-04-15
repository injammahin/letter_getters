<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChildMessage extends Model
{
    protected $fillable = [
        'child_conversation_id',
        'sender_user_id',
        'receiver_user_id',
        'message',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function conversation()
    {
        return $this->belongsTo(ChildConversation::class, 'child_conversation_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_user_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_user_id');
    }
}