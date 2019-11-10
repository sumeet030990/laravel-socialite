<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceScopeUser extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'loginType_id', 'scope_id', 'permission'
    ];

    public function scope(): BelongsTo
    {
        return $this->belongsTo(LoginServiceScope::class, 'scope_id');
    }
}
