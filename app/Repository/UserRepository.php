<?php


namespace App\Repository;


use App\Model\User;

class UserRepository extends Repository
{
    /** @var model */
    protected $model;

    /**
     * @param $model
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }
}
