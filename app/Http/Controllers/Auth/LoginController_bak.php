<?php

namespace App\Http\Controllers\Auth;

use App\Model\User;
use App\Services\UserService;
use App\Services\LoginTypeService;
use App\Services\SocialiteService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Two\User as SocialiteUser;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;
    
    /** 
     * @var SocialiteService $socialiteService 
     */
    private $socialiteService;

    /** 
     * @var LoginTypeService $loginTypeService 
     */
    private  $loginTypeService;

    /**
     * @var UserService $userService
     */
    private $userService;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        SocialiteService $socialiteService,
        LoginTypeService $loginTypeService,
        UserService $userService
    ) {
        $this->middleware('guest')->except('logout');
        $this->socialiteService = $socialiteService;
        $this->loginTypeService = $loginTypeService;
        $this->userService = $userService;
    }

    /**
     * Redirect the user to the Service authentication page.
     *
     * @param String $service
     * @return RedirectResponse|bool
     */
    public function redirectToProvider(String $service)
    {
        if (!$this->loginTypeService->checkLoginTypeExist($service)) {
            return back();
        }
        
        return $this->socialiteService->redirectToProviderService($this->loginTypeService->getLoginTypeByName($service));
    }

    /**
     * Obtain the user information from requested service.
     *
     * @param String $service
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback(String $service)
    {
        $eloquentUser = null;
        $socialiteUser = $this->socialiteService->getUserDetail($service);
        $serviceModel = $this->loginTypeService->getLoginTypeByName($service);
        $eloquentUser = $this->userService->fetchUserIfEmailExist($socialiteUser->email);
        if ($eloquentUser instanceof User) { //if email id exists in db
            if (!$this->userService->checkUserLoginType($eloquentUser, $service)) { // check login service type exist in db
                $this->userService->storeUserLoginType($eloquentUser, $serviceModel->id, $socialiteUser); //store only login type for the user
            }
        } else {
            $eloquentUser = $this->storeUser($socialiteUser, $serviceModel->id); //store entry in db
        }

        $eloquentUser->user_login_type = $service; // set the login type of user
        Auth::login($eloquentUser);
        session(['user_login_type'=>$serviceModel]);

        return redirect('home');
    }

    /**
     * Store User
     *
     * @param SocialiteUser $socialiteUser
     * @param int $serviceId
     * @return User
     */
    private function storeUser(SocialiteUser $socialiteUser, int $serviceId): User
    {
        if($serviceId == 2) {
            return $this->userService->storeFaceBookUser($socialiteUser, $serviceId);
        } 
    }
}
