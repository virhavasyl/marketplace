<?php

namespace App\Models;

use App\Traits\HasTranslations;
use App\Traits\Tree;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Location
 * @package App\Models
 */
class Location extends Model
{
    use HasTranslations, Tree;

    const TYPE_REGION = 1;
    const TYPE_DISTRICT = 2;
    const TYPE_LOCALITY = 3;

    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    public $translatedAttributes = [
        'title'
    ];

    /**
     * Translatable model.
     *
     * @var string
     */
    public $translationModel = 'App\Models\Translation\LocationTranslation';

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
        'type',
        'parent_id'
    ];

    /**
     * Scope a query to only include regions.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeRegions(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_REGION);
    }

    /**
     * Scope a query to only include districts.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeDistricts(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_DISTRICT);
    }

    /**
     * Scope a query to only include localities.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeLocalities(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_LOCALITY);
    }

    /**
     * Get list of all types.
     *
     * @return array
     */
    public static function getTypes(): array
    {
        return [
            self::TYPE_REGION => trans('admin::location.type.region'),
            self::TYPE_DISTRICT => trans('admin::location.type.district'),
            self::TYPE_LOCALITY => trans('admin::location.type.locality')
        ];
    }

    /**
     * Get hierarchical tree of all regions and districts
     *
     * @param array $columns - Columns to select.
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function getRegionsAndDistricts($columns = ['id'])
    {
        return self::with(['children' => function ($query) use ($columns) {
            $query->districts();
        }])->joinTranslate()
            ->select($columns)
            ->root()
            ->regions()
            ->orderBy('title')
            ->get();
    }
}
