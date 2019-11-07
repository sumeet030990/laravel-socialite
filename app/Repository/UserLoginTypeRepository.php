<?php


namespace App\Repository;


use App\Model\UserLoginType;

class UserLoginTypeRepository extends Repository
{
    /** @var model */
    protected $model;

    /**
     * @param $model
     */
    public function __construct(UserLoginType $model)
    {
        $this->model = $model;
    }
}
