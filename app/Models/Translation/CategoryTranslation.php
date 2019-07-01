<?php

namespace App\Models\Translation;

use App\Traits\HasCompositePrimaryKey;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CategoryTranslation
 * @package App\Models\Translation
 */
class CategoryTranslation extends Model
{
    use HasCompositePrimaryKey;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /** Indicates if the model should use autoincrementing
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description'
    ];

    /**
     * Primary key
     *
     * @var array
     */
    protected $primaryKey = [
        'category_id',
        'locale'
    ];
}
