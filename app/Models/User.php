<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'account_status',
        'parent_email',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function interests()
    {
        return $this->belongsToMany(Interest::class, 'user_interests');
    }

    public function parentApprovals()
    {
        return $this->hasMany(ParentApproval::class, 'child_user_id');
    }
    public function hasCompletedChildProfile(): bool
    {
        if ($this->role !== 'child') {
            return true;
        }

        $profile = $this->profile;

        return $profile
            && filled($profile->avatar)
            && filled($profile->avatar_type)
            && !is_null($profile->profile_completed_at)
            && $this->interests()->exists();
    }
}