<?php

namespace App\Repository;

use App\Model\LoginServiceScope;

class LoginServiceScopeRepository extends Repository
{
    /** @var model */
    protected $model;

    /**
     * @param $model
     */
    public function __construct(LoginServiceScope $model)
    {
        $this->model = $model;
    }
}
