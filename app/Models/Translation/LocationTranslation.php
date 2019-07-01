<?php

namespace App\Models\Translation;

use App\Traits\HasCompositePrimaryKey;
use Illuminate\Database\Eloquent\Model;

/**
 * Class LocationTranslation
 * @package App\Models\Translation
 */
class LocationTranslation extends Model
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
        'title'
    ];

    /**
     * Primary key
     *
     * @var array
     */
    protected $primaryKey = [
        'location_id',
        'locale'
    ];
}
