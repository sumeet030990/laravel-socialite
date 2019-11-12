<?php


namespace App\Repository;

use App\Model\User;
use App\Model\ServiceScopeUser;
use App\Model\LoginServiceScope;
use Illuminate\Database\Eloquent\Collection;

class ServiceScopeUserRepository extends Repository
{
    /** @var model */
    protected $model;

    /**
     * @param $model
     */
    public function __construct(ServiceScopeUser $model)
    {
        $this->model = $model;
    }

    /**
     * Undocumented function
     *
     * @param Array $data
     * @return bool
     */
    public function insertAllScope(Array $data): bool
    {
        return $this->model->insert($data);
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
        foreach($data as $key => $value) {
            $this->model->where('user_id', $user->id)
            ->where('scope_id', $key)
            ->update([
                'permission' => $value
            ]);
        }
    }
    
    public function updateUserDeclinedServiceScopeate(User $user, LoginServiceScope $loginServiceScope)
    {
        $this->model->where('user_id', $user->id)
        ->where('scope_id', $loginServiceScope->id)
        ->where('permission', true)
        ->update([
            'permission' => false
        ]);
    }
}
