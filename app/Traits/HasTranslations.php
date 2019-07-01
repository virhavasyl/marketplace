<?php

namespace App\Traits;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;

/**
 * Trait Translatable
 * @package App\Traits
 */
trait HasTranslations
{
    use Translatable;

    /**
     * Join translation table.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeJoinTranslate(Builder $query): Builder
    {
        return $query->join("{$this->getTranslationsTable()} as t", function ($join) {
            $join->on("{$this->getTable()}.{$this->getKeyName()}", '=', "t.{$this->getRelationKey()}")
                ->where("t.{$this->getLocaleKey()}", $this->locale());
        });
    }
}
