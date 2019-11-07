<?php


namespace App\Services;


use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User;

class SocialiteService
{

    /**
     * Redirect the user to the Service authentication page.
     *
     * @param String $service
     * @return RedirectResponse
     */
    public function redirectToProviderService(String $service): RedirectResponse
    {
        return Socialite::driver($service)
            ->scopes(config('constants.SERVICE_SCOPE.' . $service))
            ->redirect();
    }

    /** Get User details
     *
     * @param String $service
     * @return User
     */
    public function getUserDetail(String $service): User
    {
        return Socialite::driver($service)
            ->stateless()
            ->user();
    }
}
