<?php


namespace App\Services;

use App\Model\LoginType;
use App\Model\User;
use App\Model\UserLoginType;
use App\Repository\LoginServiceScopeRepository;
use App\Repository\UserRepository;
use Illuminate\Support\Facades\Hash;
use App\Repository\UserLoginTypeRepository;
use App\Repository\ServiceScopeUserRepository;
use Illuminate\Support\Facades\Auth;
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
        UserLoginTypeRepository $userLoginTypeRepository,
        ServiceScopeUserRepository $serviceScopeUserRepository,
        LoginServiceScopeRepository $loginServiceScopeRepository
    ) {
        $this->userRepository = $userRepository;
        $this->userLoginTypeRepository = $userLoginTypeRepository;
        $this->serviceScopeUserRepository = $serviceScopeUserRepository;
        $this->loginServiceScopeRepository = $loginServiceScopeRepository;
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

        $userLoginType = $this->userLoginTypeRepository->findWhere([
            'user_id' => $user->id, 
            'loginType_id' => $serviceId
        ]);

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
    public function storeFaceBookUser(SocialiteUser $socialiteUser, int $serviceId): User
    {
        $user = $this->userRepository->store([
            'name' => $socialiteUser->name,
            'email' => $socialiteUser->email,
            'avatar_thumbnail' => $socialiteUser->avatar,
            'avatar' => $socialiteUser->avatar_original,
            'current_city' => isset($socialiteUser->user['location']) ? $socialiteUser->user['location']['name']: null,
            'hometown' => isset($socialiteUser->user['hometown'])? $socialiteUser->user['hometown']['name']: null,
            'age_range' => isset($socialiteUser->user['age_range'])? $socialiteUser->user['age_range']['min']: null,
            'date_of_birth' => isset($socialiteUser->user['birthday'])? $socialiteUser->user['birthday']: null,
        ]);

        $this->storeUserLoginType($user, $serviceId, $socialiteUser);
        $this->storeUserPermissions($user, $serviceId);

        return $user;
    }


    /**
     * Store user in db
     *
     * @param SocialiteUser $socialiteUser
     * @param int $serviceId
     * @return User
     */
    public function updateFaceBookUser(User $user, SocialiteUser $socialiteUser)
    {
        return $this->userRepository->update($user->id, [
            'current_city' => isset($socialiteUser->user['location']) ? $socialiteUser->user['location']['name']: null,
            'hometown' => isset($socialiteUser->user['hometown'])? $socialiteUser->user['hometown']['name']: null,
            'age_range' => isset($socialiteUser->user['age_range'])? $socialiteUser->user['age_range']['min']: null,
            'date_of_birth' => isset($socialiteUser->user['birthday'])? $socialiteUser->user['birthday']: null,
            'gender' => isset($socialiteUser->user['gender'])? $socialiteUser->user['gender']: null
        ]);
    }

    /**
     * Store User Login Type
     *
     * @param User $user
     * @param integer $serviceId
     * @param SocialiteUser $socialiteUser
     * @return void
     */
    public function storeUserLoginType(User $user, int $serviceId, SocialiteUser $socialiteUser=null)
    {
        return $this->userLoginTypeRepository->store([
            'user_id' => $user->id, 
            'loginType_id' => $serviceId,
            'token' => isset($socialiteUser->token)? $socialiteUser->token: null,
            'refresh_token' => isset($socialiteUser->refreshToken)? $socialiteUser->refreshToken: null,
            'expires_in' => isset($socialiteUser->expiresIn)? $socialiteUser->expiresIn: null,
            'service_id' => isset($socialiteUser->id)? $socialiteUser->id: null
            ]
        );
    }

    /**
     * Store User Permission
     *
     * @param User $user
     * @param int $serviceId
     * @return void
     */
    public function storeUserPermissions(User $user, int $serviceId)
    {
        $data = [];
        $scopes = $this->loginServiceScopeRepository->findAllWhere([
            'loginType_id' => $serviceId
        ]);
        foreach ($scopes as $scope) {
            array_push($data, [
                'user_id' => $user->id, 
                'loginType_id' => $serviceId,
                'scope_id' => $scope->id,
                'permission' => false
            ]);
        }
        
        $this->serviceScopeUserRepository->insertAllScope($data);
    }

    public function getPendingPermissionForService(User $user, LoginType $loginType)
    {
        $scope = [];
        $userPermissions = $user->permissionNotGivenScopes
        ->where('loginType_id', $loginType->id);
        foreach($userPermissions as $userPermission){
            array_push($scope, [
                'id' => $userPermission->scope->id,
                'name' => $userPermission->scope->scope
            ]);
        }
        
        return $scope;
    }

    public function getUserScopeForService(User $user, LoginType $loginType)
    {
        $scope = [];
        $userPermissions = $user->permissionGivenScopes
        ->where('loginType_id', $loginType->id);
        foreach($userPermissions as $userPermission){
            $scope[] = $userPermission->scope->scope;
        }
        
        return $scope;
    }
}
