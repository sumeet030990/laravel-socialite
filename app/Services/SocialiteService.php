<?php

namespace App\Services;

use App\Model\User;
use GuzzleHttp\Client;
use App\Model\LoginType;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;
use App\Services\ServiceScopeUserService;
use App\Services\LoginServiceScopeService;
use Laravel\Socialite\Two\User as SocialiteUser;

class SocialiteService
{
    /**
     * @var UserLoginTypeService
     */
    private $userLoginTypeService;

    /**
     * @var UserService
     */
    private $userService;

    public function __construct(
        UserLoginTypeService $userLoginTypeService,
        UserService $userService,
        Client $client,
        LoginServiceScopeService $loginServiceScopeService,
        ServiceScopeUserService $serviceScopeUserService
    ) {
        $this->userLoginTypeService = $userLoginTypeService;
        $this->userService = $userService;
        $this->client = $client;
        $this->loginServiceScopeService = $loginServiceScopeService;
        $this->serviceScopeUserService = $serviceScopeUserService;
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
     * @return SocialiteUser
     */
    public function getUserDetail(String $service): SocialiteUser
    {
        $user = Auth::user();
        if (Auth::check() && $service=="facebook") {
            $socialiteUser = Socialite::driver($service)
            ->stateless()
            ->fields($this->getFieldsForService($user))
            ->user();

            $this->updateUserServiceScopeForDeclinedRequest($user);
            $this->userService->updateFaceBookUser($user, $socialiteUser);
            dd($socialiteUser);
        }
        
        return Socialite::driver($service)
        ->stateless()
        ->user();
    }

    /**
     * Get Field Name For the Facebook Service
     *
     * @param User $user
     * @return array
     */
    private function getFieldsForService(User $user): array
    {
        $fields = ['first_name', 'last_name', 'email'];
        foreach ($user->permissionGivenScopes->where('loginType_id', 2) as $userScope) {
            $fields[] = $userScope->scope->field_name;
        }

        return $fields;
    }

    private function updateUserServiceScopeForDeclinedRequest(User $user)
    {
        $request = $this->client->get(config('constants.SERVICE_SCOPE.facebook.url') . '?access_token='.Auth::user()->user_loginType->token);
        $response = json_decode($request->getBody()->getContents(), true);
        foreach ($response['data'] as $key => $value) {
            if($value['status'] == 'declined') {
                $loginServiceScope = $this->loginServiceScopeService->getLoginServiceScopeByName($value['permission']);
                $this->serviceScopeUserService->updateUserDeclinedServiceScope(Auth::user(), $loginServiceScope);
            }
        }
    }
}
