<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChildHabitatLayout extends Model
{
    protected $fillable = [
        'child_habitat_id',
        'asset_id',
        'x',
        'y',
        'scale',
        'rotation',
        'direction',
        'z_index',
        'is_visible',
    ];

    protected $casts = [
        'x' => 'float',
        'y' => 'float',
        'scale' => 'float',
        'rotation' => 'float',
        'z_index' => 'integer',
        'is_visible' => 'boolean',
    ];

    public function habitat(): BelongsTo
    {
        return $this->belongsTo(ChildHabitat::class, 'child_habitat_id');
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(HabitantAsset::class, 'asset_id');
    }
}