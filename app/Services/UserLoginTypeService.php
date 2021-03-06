<?php


namespace App\Services;

use App\Model\User;
use App\Model\UserLoginType;
use App\Repository\UserLoginTypeRepository;

class UserLoginTypeService
{
    /** @var UserLoginTypeRepository userLoginTypeRepository */
    private $userLoginTypeRepository;

    public function __construct(UserLoginTypeRepository $userLoginTypeRepository)
    {
        $this->userLoginTypeRepository = $userLoginTypeRepository;
    }

    /**
     * @param User $user
     * @return UserLoginType
     */
    public function assignNormalLoginTypeToUser(User $user): UserLoginType
    {
        return $this->userLoginTypeRepository->store([
            'user_id' => $user->id,
            'loginType_id' => 3
        ]);
    }
}
