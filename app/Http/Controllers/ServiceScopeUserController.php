<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\ServiceScopeUser;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Services\ServiceScopeUserService;
use App\Services\UserService;
use Illuminate\Database\Eloquent\Collection;

class ServiceScopeUserController extends Controller
{
    /**
     * @var ServiceScopeUserService
     */
    private $serviceScopeUserService;

    /**
     * @var UserService
     */
    private $userService;

    public function __construct(
        ServiceScopeUserService $serviceScopeUserService,
        UserService $userService
    ) {
        $this->serviceScopeUserService = $serviceScopeUserService;
        $this->userService =$userService;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->serviceScopeUserService->updatePermission(Auth::user(), $request->all());
        $scope = $this->userService->getUserScopeForService(Auth::user(), session('user_login_type'));

        return Socialite::driver(session('user_login_type')->name)
        ->with(['auth_type' => 'rerequest'])
        ->scopes($scope)
        ->redirect();
    }

    /**
     * Get User Peding Permissions for the logged in Service 
     *
     * @return Array
     */
    public function getServicesPendingPermissions(): Array
    {
        return $this->userService->getPendingPermissionForService(Auth::user(), session('user_login_type'));
    }
}
