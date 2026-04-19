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
        'email_verified_at',
        'coin_balance',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'coin_balance' => 'integer',
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

        if (! $profile) {
            return false;
        }

        $hasAvatar = false;

        if ($profile->avatar_type === 'upload' && filled($profile->avatar)) {
            $hasAvatar = true;
        }

        if ($profile->avatar_type === 'library' && ! is_null($profile->avatar_library_id)) {
            $hasAvatar = true;
        }

        $hasBasicInfo =
            filled($profile->display_name) &&
            filled($profile->age_or_grade) &&
            filled($profile->city) &&
            filled($profile->state) &&
            filled($profile->short_bio);

        $hasInterests = $this->interests()->exists();

        return $hasAvatar
            && $hasBasicInfo
            && $hasInterests
            && ! is_null($profile->profile_completed_at);
    }

    public function sentChildMatchRequests()
    {
        return $this->hasMany(ChildMatchRequest::class, 'requester_user_id');
    }

    public function receivedChildMatchRequests()
    {
        return $this->hasMany(ChildMatchRequest::class, 'target_user_id');
    }

    public function childMatchesAsOne()
    {
        return $this->hasMany(ChildMatch::class, 'user_one_id');
    }

    public function childMatchesAsTwo()
    {
        return $this->hasMany(ChildMatch::class, 'user_two_id');
    }

    public function parentLink()
    {
        return $this->hasOne(ParentChildLink::class, 'child_user_id');
    }

    public function sentChildMessages()
    {
        return $this->hasMany(ChildMessage::class, 'sender_user_id');
    }

    public function receivedChildMessages()
    {
        return $this->hasMany(ChildMessage::class, 'receiver_user_id');
    }

    public function sentChildLetters()
    {
        return $this->hasMany(ChildLetter::class, 'sender_user_id');
    }

    public function receivedChildLetters()
    {
        return $this->hasMany(ChildLetter::class, 'receiver_user_id');
    }

    public function cartItems()
    {
        return $this->hasMany(\App\Models\CartItem::class);
    }

    public function orders()
    {
        return $this->hasMany(\App\Models\Order::class);
    }

    public function coinTransactions()
    {
        return $this->hasMany(\App\Models\CoinTransaction::class)->latest();
    }
}