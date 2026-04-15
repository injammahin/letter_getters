<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChildConversation extends Model
{
    protected $fillable = [
        'child_match_id',
    ];

    public function match()
    {
        return $this->belongsTo(ChildMatch::class, 'child_match_id');
    }

    public function messages()
    {
        return $this->hasMany(ChildMessage::class)->latest();
    }
}