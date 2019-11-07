<?php


namespace App\Services;



use App\Model\LoginType;
use App\Repository\LoginTypeRepository;

class LoginTypeService
{
    /** @var LoginTypeRepository loginTypeRepository */
    private $loginTypeRepository;

    public function __construct(LoginTypeRepository $loginTypeRepository)
    {
        $this->loginTypeRepository = $loginTypeRepository;
    }
    
    /**
     * @param String $service
     * @return bool
     */
    public function checkLoginTypeExist(String $service): bool
    {
        return $this->loginTypeRepository->existsWhere('name', $service);
    }

    /**
     * @param String $service
     * @return LoginType
     */
    public function getLoginTypeByName(String $service): LoginType
    {
        return $this->loginTypeRepository->findWhere(['name' => $service]);
    }
}
