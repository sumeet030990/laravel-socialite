<?php


namespace App\Repository;


use App\Model\LoginType;

class LoginTypeRepository extends Repository
{
    /** @var model */
    protected $model;

    /**
     * @param $model
     */
    public function __construct(LoginType $model)
    {
        $this->model = $model;
    }
}
