<?php

namespace App\Services;

use App\Model\LoginType;
use App\Model\UserLoginType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User;

class SocialiteService
{
    /**
     * @var UserLoginTypeService
     */
    private $userLoginTypeService;

    public function __construct(UserLoginTypeService $userLoginTypeService) {
        $this->userLoginTypeService = $userLoginTypeService;
    }

    /**
     * Redirect the user to the Service authentication page.
     *
     * @param StrLoginTypeing $service
     * @return RedirectResponse
     */
    public function redirectToProviderService(LoginType $service): RedirectResponse
    {
        $scope = [];
        $user = Auth::user();
        if (!is_null($user)) {
            $scope = $user->permissionGivenScopes;
        }
        return Socialite::driver($service->name)
            ->scopes($scope)
            ->redirect();
    }

    /** Get User details
     *
     * @param String $service
     * @return User
     */
    public function getUserDetail(String $service): User
    {
        $user =  Socialite::driver($service)
            ->stateless();
        if($service=="facebook") {
            $user->fields($this->getFieldsForService($user->user()));
        }

        return $user->user();
    }

    /**
     * Get Field Name For the Facebook Service
     *
     * @param User $user
     * @return array
     */
    private function getFieldsForService(User $user): array
    {
        $fields = [];
        $userLoginTypeModel = $this->userLoginTypeService->getUserByServiceId($user);
        if ($userLoginTypeModel instanceof UserLoginType) {
            foreach ($userLoginTypeModel->user->permissionGivenScopes->where('loginType_id', 2) as $userScope) {
                $fields[] = $userScope->scope->field_name;
            }
        }

        return $fields;
    }
}
