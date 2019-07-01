<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Setting
 *
 * @package App\Models
 */
class Setting extends Model
{
    const CATEGORY_GENERAL = 1;
    const CATEGORY_SOCIAL = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id',
        'key',
        'value',
    ];

    /**
     * Set additional attributes.
     *
     * @var array
     */
    protected $appends = [
        'category_text',
    ];

    public $timestamps = false;

    /**
     * Get all settings categories.
     *
     * @param int|null $category
     * @return array|string|null
     */
    public static function getCategories($category = null)
    {
        $data = [
            self::CATEGORY_GENERAL => trans('admin::setting.category.general'),
            self::CATEGORY_SOCIAL => trans('admin::setting.category.social'),
        ];

        if ($category !== null) {
            return isset($data[$category]) ? $data[$category] : null;
        }

        return $data;
    }

    /**
    * Get setting category.
    *
    * @return string|null
    */
    public function getCategoryTextAttribute()
    {
        return self::getCategories($this->category_id);
    }

    /**
     * Get value by key.
     *
     * @param $key
     *
     * @return null
     */
    public static function getValue($key)
    {
        $setting = static::where('key', $key)->first();

        return $setting ? $setting->value : null;
    }
}
