<?php


namespace App\Services;

use App\Model\User;
use App\Model\UserLoginType;
use App\Repository\UserRepository;
use Illuminate\Support\Facades\Hash;
use App\Repository\UserLoginTypeRepository;
use Laravel\Socialite\Two\User as SocialiteUser;

class UserService
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var UserLoginTypeRepository
     */
    private $userLoginTypeRepository;

    /**
     * Create a new service instance.
     *
     * @param UserRepository $userRepository
     * @param UserLoginTypeRepository $userLoginTypeRepository
     */
    public function __construct(
        UserRepository $userRepository,
        UserLoginTypeRepository $userLoginTypeRepository
    ) {
        $this->userRepository = $userRepository;
        $this->userLoginTypeRepository = $userLoginTypeRepository;
    }

    /**
     * @param String $service
     * @return bool
     */
    public function checkLoginTypeExist(String $service): bool
    {
        return $this->userRepository->existsWhere('name', $service);
    }

    /**
     * @param String $email
     * @return User| null
     */
    public function fetchUserIfEmailExist(String $email)
    {
        return $this->userRepository->findWhere(['email' => $email]);
    }

    /**
     * Check if User exist for the input service 
     *
     * @param User $user
     * @param String $service
     * @return boolean
     */
    public function checkUserLoginType(User $user, String $service): bool
    {
        if ($user->loginType->contains('name', $service)) {
            return true;
        }
        
        return false;
    }

    /**
     * Save User for Normal login type
     *
     * @param array $userData
     * @param int $serviceId
     * @return User
     */
    public function saveNormalUser(array $userData, int $serviceId): User
    {
        
        $user = $this->userRepository->findWhere(['email' => $userData['email']]);

        if (!$user instanceof User) { //if user does not exist in database
            $user = $this->userRepository->firstOrCreate([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make($userData['password']),
            ]);
        }

        if ($user instanceof User && is_null($user->password)) {//if user exist & password is not there
            $this->userRepository->update($user->id,[
                'password' => Hash::make($userData['password']),
            ]);
        }

        $userLoginType = $this->userLoginTypeRepository->findWhere(['user_id' => $user->id, 'loginType_id' => $serviceId]);
        if (!$userLoginType instanceof UserLoginType) {
            $this->storeUserLoginType($user, $serviceId);
        }

        return $user;
    }
    /**
     * Store user in db
     *
     * @param SocialiteUser $socialiteUser
     * @param int $serviceId
     * @return User
     */
    public function storeUser(SocialiteUser $socialiteUser, int $serviceId): User
    {
        $user = $this->userRepository->firstOrCreate([
            'name' => $socialiteUser->name,
            'email' => $socialiteUser->email
        ]);

        $this->storeUserLoginType($user, $serviceId);

        return $user;
    }

    /**
     * Store User Login Type
     *
     * @param User $user
     * @param integer $serviceId
     * @return void
     */
    public function storeUserLoginType(User $user, int $serviceId)
    {
        return $this->userLoginTypeRepository->store([
            'user_id' => $user->id, 
            'loginType_id' => $serviceId
            ]
        );
    }
}
