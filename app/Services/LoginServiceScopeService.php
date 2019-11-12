<?php

namespace App\Services;

use App\Model\LoginServiceScope;
use App\Repository\LoginServiceScopeRepository;

class LoginServiceScopeService
{
    public function __construct(LoginServiceScopeRepository $loginServiceScopeRepository) {
        $this->loginServiceScopeRepository = $loginServiceScopeRepository;
    }

    public function getLoginServiceScopeByName(String $scopeName): LoginServiceScope
    {
        return $this->loginServiceScopeRepository->findWhere([
            'scope' => $scopeName
        ]);
    }
}
