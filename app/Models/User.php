<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User
 * @package App\Models
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    const STATUS_PENDING = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_BLOCKED = -1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'password',
        'firstname',
        'lastname',
        'description',
        'role_id',
        'phone',
        'avatar_path',
        'fb_link',
        'instagram_link',
        'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at'
    ];

    /**
     * Set additional attributes.
     *
     * @var array
     */
    protected $appends = [
        'status_text',
        'fullname',
        'is_active'
    ];

    /**
     * Get the role for the users.
     *
     * @return BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo('App\Models\Role');
    }

    /**
    * Get user status.
    *
    * @return string|null
    */
    public function getStatusTextAttribute()
    {
        return self::getStatuses($this->status);
    }

    /**
    * Get user fullname.
    *
    * @return string
    */
    public function getFullnameAttribute(): string
    {
        return trim("{$this->firstname} {$this->lastname}");
    }

    /**
    * Check user status.
    *
    * @return true|false
    */
    public function getIsActiveAttribute()
    {
        return self::STATUS_ACTIVE == $this->status ? true : false;
    }

    /**
     * Get all status
     *
     * @param int|null $status
     * @return array|string|null
     */
    public static function getStatuses($status = null)
    {
        $data = [
            self::STATUS_PENDING => trans('admin::user.status.inactive'),
            self::STATUS_ACTIVE => trans('admin::user.status.active'),
            self::STATUS_BLOCKED => trans('admin::user.status.blocked')
        ];

        if ($status !== null) {
            return isset($data[$status]) ? $data[$status] : null;
        }

        return $data;
    }
}
