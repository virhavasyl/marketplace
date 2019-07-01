<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Role
 * @package App\Models
 */
class Role extends Model
{

    const ADMINISTRATOR = 1;
    const USER = 2;
    const STORE = 3;

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
        'title',
    ];

    /**
     * Get all of the users for the role.
     *
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany('App\Models\User');
    }
}
