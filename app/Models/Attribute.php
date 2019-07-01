<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Attribute
 * @package App\Models
 */
class Attribute extends Model
{
    use HasTranslations;

    const TYPE_TICK = 1; // Attribute type checkbox (Yes/No)
    const TYPE_LIST = 2; // Attribute type list (one of many)

    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    public $translatedAttributes = [
        'title',
    ];

    /**
     * Translatable model.
     *
     * @var string
     */
    public $translationModel = 'App\Models\Translation\AttributeTranslation';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type'
    ];

    /**
     * Set additional attributes.
     *
     * @var array
     */
    protected $appends = [
        'type_text',
        'is_tick_type'
    ];

    /**
     * The categories that belong to the attribute.
     *
     * @return BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\Category', 'attribute_has_category');
    }

    /**
     * The variations oof the attribute.
     *
     * @return HasMany
     */
    public function variations(): HasMany
    {
        return $this->hasMany('App\Models\AttributeVariation');
    }

    /**
     * Get all attribute types.
     *
     * @param null $type
     * @return array|string|null
     */
    public static function getTypes($type = null)
    {
        $data = [
            self::TYPE_TICK => trans('admin::attribute.type.tick'),
            self::TYPE_LIST => trans('admin::attribute.type.list'),
        ];

        if ($type !== null) {
            return isset($data[$type]) ? $data[$type] : null;
        }

        return $data;
    }

    /**
     * Scope a query to filter by categories.
     *
     * @param Builder $query
     * @param mixed $category_ids
     * @return Builder
     */
    public function scopeCategory(Builder $query, $categoryIds = null): Builder
    {
        if (!$categoryIds) {
            return $query;
        }

        if (!is_array($categoryIds)) {
            $categoryIds = [$categoryIds];
        }

        return $query->whereHas('categories', function ($q) use ($categoryIds) {
            $q->whereIn('id', $categoryIds);
        });
    }

    /**
     * Get type text.
     *
     * @return string|null
     */
    public function getTypeTextAttribute()
    {
        return self::getTypes($this->type);
    }
    
    /**
    * Check attribute type.
    *
    * @return true|false
    */
    public function getIsTickTypeAttribute()
    {
        return self::TYPE_TICK == $this->type;
    }
}
