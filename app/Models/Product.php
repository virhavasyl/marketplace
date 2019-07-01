<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Product
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property integer $category_id
 * @property integer $user_id
 * @property float $price
 * @property integer $currency_id
 * @property integer $status
 * @property integer $condition
 * @property integer $in_store
 * @package App\Models
 */
class Product extends Model
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    const CONDITION_NEW = 1;
    const CONDITION_USED = 2;
    const CONDITION_FOR_PARTS_OR_NOT_WORKING = 3;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'category_id',
        'user_id',
        'price',
        'currency_id',
        'status',
        'condition'
    ];

    /**
     * Set additional attributes.
     *
     * @var array
     */
    protected $appends = [
        'status_text',
        'is_active',
        'condition_text'
    ];

    /**
     * Get the category for the product.
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo('App\Models\Category');
    }

    /**
     * Get the user for the product.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Get the currency for the product.
     *
     * @return BelongsTo
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo('App\Models\Currency');
    }

    /**
     * Get all of the images for the product.
     *
     * @return HasMany
     */
    public function images(): HasMany
    {
        return $this->hasMany('App\Models\ProductImage');
    }

    /**
     * The attributes that belong to the product.
     *
     * @return BelongsToMany
     */
    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\Attribute', 'product_has_attribute')->withPivot('value');
    }

    /**
     * Get all statuses.
     *
     * @param int|null $status
     * @return array|string|null
     */
    public static function getStatuses($status = null)
    {
        $data = [
            self::STATUS_INACTIVE => trans('admin::product.status.inactive'),
            self::STATUS_ACTIVE => trans('admin::product.status.active'),
        ];

        if ($status !== null) {
            return isset($data[$status]) ? $data[$status] : null;
        }

        return $data;
    }

    /**
    * Get product status.
    *
    * @return string|null
    */
    public function getStatusTextAttribute()
    {
        return self::getStatuses($this->status);
    }

    /**
    * Check product status.
    *
    * @return bool
    */
    public function getIsActiveAttribute(): bool
    {
        return self::STATUS_ACTIVE == $this->status ? true : false;
    }

    /**
     * Get all conditions
     *
     * @param int|null $condition
     * @return array|string|null
     */
    public static function getConditions($condition = null)
    {
        $data = [
            self::CONDITION_NEW => trans('admin::product.condition.new'),
            self::CONDITION_USED => trans('admin::product.condition.used'),
            self::CONDITION_FOR_PARTS_OR_NOT_WORKING => trans('admin::product.condition.for_parts_or_not_working'),
        ];

        if ($condition !== null) {
            return isset($data[$condition]) ? $data[$condition] : null;
        }

        return $data;
    }

    /**
    * Get product condition.
    *
    * @return string|null
    */
    public function getConditionTextAttribute()
    {
        return self::getConditions($this->condition);
    }

    /**
     * Make attribute/variation array of product.
     *
     * @param bool $skip_missing_tick
     * @param string $key
     *
     * @return array
     */
    public function makeAttributeVariationArray($skip_missing_tick = true, $key = 'attributes'): array
    {
        $result = [];
        $attributes = $this->attributes()->get();
        foreach ($attributes as $attribute) {
            if ($attribute->type == Attribute::TYPE_TICK && $skip_missing_tick && !old($key . '.' . $attribute->id)) {
                $result[$attribute->id] = null;
            } else {
                $result[$attribute->id] =  $attribute->pivot->value;
            }
        }

        return $result;
    }
}
