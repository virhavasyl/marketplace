<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Trait Tree
 * @package App\Traits
 */
trait Tree
{
    /**
     * Get the children.
     *
     * @return HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(static::class, 'parent_id');
    }

    /**
     * Get parent entity.
     *
     * @return BelongsTo
     */
    public function parentEntity(): BelongsTo
    {
        return $this->belongsTo(static::class, 'parent_id');
    }

    /**
     * Scope a query to only include root locations.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeRoot(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }
}
