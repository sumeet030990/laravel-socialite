<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LoginServiceScope extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'loginType_id', 'scope_id', 'permission'
    ];
}
