<?php

namespace App\Model;

use App\Model\ServiceScopeUser;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar_thumbnail', 'avatar', 'age_range', 'date_of_birth', 'current_city', 'hometown'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * User Relationship with various login's
     *
     * @return BelongsToMany
     */
    public function loginType(): BelongsToMany
    {
        return $this->belongsToMany(LoginType::class, 'user_loginType',  'user_id', 'loginType_id');
    }

    /**
     * Relationship with scope function where permission are given
     *
     * @return HasMany
     */
    public function permissionGivenScopes(): HasMany
    {
        return $this->hasMany('App\Model\ServiceScopeUser')
            ->where('permission', true);
    }

    /**
     * Relationship with scope function where permission are not given
     *
     * @return HasMany
     */
    public function permissionNotGivenScopes(): HasMany
    {
        return $this->hasMany('App\Model\ServiceScopeUser')
            ->where('permission', false);
    }

}
