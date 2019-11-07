<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserLoginType extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_loginType';
    
    protected $primaryKey = null;
    
    public $incrementing = false;
    
    public $timestamps = false;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'loginType_id',
    ];
}
