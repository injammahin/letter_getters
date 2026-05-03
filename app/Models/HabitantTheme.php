<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class HabitantTheme extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'thumbnail_image',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function assets(): HasMany
    {
        return $this->hasMany(HabitantAsset::class, 'theme_id');
    }

    public function activeAssets(): HasMany
    {
        return $this->assets()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name');
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        return $this->thumbnail_image
            ? Storage::url($this->thumbnail_image)
            : null;
    }
}