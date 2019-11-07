<?php

namespace App\Http\Controllers\Auth;

use App\Model\User;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Services\LoginTypeService;
use App\Http\Controllers\Controller;
use App\Services\UserLoginTypeService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * @var UserLoginTypeService
     */
    private $userLoginTypeService;

    /**
     * @var UserService
     */
    private $userService;
    
    /**
     * @var LoginTypeService
     */
    private $loginTypeService;

    /**
     * Where to redirect users after registration.
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
        UserLoginTypeService $userLoginTypeService,
        UserService $userService,
        LoginTypeService $loginTypeService
    )
    {
        $this->middleware('guest');
        $this->userLoginTypeService = $userLoginTypeService;
        $this->userService = $userService;
        $this->loginTypeService = $loginTypeService;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $serviceId = $this->loginTypeService->getLoginTypeByName('normal')->id;

        return $this->userService->saveNormalUser($data, $serviceId);
    }
}
