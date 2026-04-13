<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChildAvatar extends Model
{
    protected $fillable = [
        'name',
        'image_path',
        'is_active',
        'sort_order',
    ];

    public function profiles()
    {
        return $this->hasMany(Profile::class, 'avatar_library_id');
    }
}