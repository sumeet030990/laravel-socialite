<?php

namespace App\Services;

use App\Model\LoginServiceScope;
use App\Model\User;
use App\Repository\ServiceScopeUserRepository;

class ServiceScopeUserService
{
    /**
     * Create a new service instance.
   
     */
    public function __construct(
        ServiceScopeUserRepository $serviceScopeUserRepository
    ) {
        $this->serviceScopeUserRepository = $serviceScopeUserRepository;
    }

    /**
     * Update permission for Logged in user
     *
     * @param User $user
     * @param Array $data
     * @return void
     */
    public function updatePermission(User $user, Array $data)
    {
        unset($data['_token']);
        return $this->serviceScopeUserRepository->updatePermission($user, $data);
    }

    public function updateUserDeclinedServiceScope(User $user, LoginServiceScope $loginServiceScope)
    {
        return $this->serviceScopeUserRepository->updateUserDeclinedServiceScopeate($user, $loginServiceScope);
    }
}
