<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class HabitantAsset extends Model
{
    protected $fillable = [
        'theme_id',
        'type',
        'name',
        'slug',
        'description',
        'image_path',
        'walking_image_path',
        'eating_image_path',
        'sad_image_path',
        'price_coins',
        'default_x',
        'default_y',
        'default_scale',
        'default_rotation',
        'default_direction',
        'default_z_index',
        'is_required',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price_coins' => 'integer',
        'default_x' => 'float',
        'default_y' => 'float',
        'default_scale' => 'float',
        'default_rotation' => 'float',
        'default_z_index' => 'integer',
        'is_required' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function theme(): BelongsTo
    {
        return $this->belongsTo(HabitantTheme::class, 'theme_id');
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image_path ? Storage::url($this->image_path) : null;
    }

    public function getWalkingImageUrlAttribute(): ?string
    {
        return $this->walking_image_path ? Storage::url($this->walking_image_path) : null;
    }

    public function getEatingImageUrlAttribute(): ?string
    {
        return $this->eating_image_path ? Storage::url($this->eating_image_path) : null;
    }

    public function getSadImageUrlAttribute(): ?string
    {
        return $this->sad_image_path ? Storage::url($this->sad_image_path) : null;
    }

    public function isBackground(): bool
    {
        return $this->type === 'background';
    }

    public function isAvatar(): bool
    {
        return $this->type === 'avatar';
    }

    public function isFood(): bool
    {
        return $this->type === 'food';
    }

    public function isToy(): bool
    {
        return $this->type === 'toy';
    }

    public function isDecoration(): bool
    {
        return $this->type === 'decoration';
    }
}