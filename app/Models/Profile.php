<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'display_name',
        'age_or_grade',
        'city',
        'state',
        'short_bio',
        'avatar',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}