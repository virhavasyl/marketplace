<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Attribute
 * @package App\Models
 */
class AttributeVariation extends Model
{
    use HasTranslations;

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
    public $translationModel = 'App\Models\Translation\AttributeVariationTranslation';

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
        'attribute_id'
    ];
}
