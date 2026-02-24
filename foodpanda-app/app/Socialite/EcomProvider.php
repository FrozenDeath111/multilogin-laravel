<?php

namespace App\Socialite;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;

class EcomProvider extends AbstractProvider implements ProviderInterface
{
    // The URL on App 1 where the user clicks "Authorize"
    private $baseUrl = "";

    public function __construct()
    {
        $this->baseUrl = config('services.ecom_sso.base_url');
    }
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase($this->baseUrl . '/oauth/authorize', $state);
    }

    // The URL where App 2 exchanges the "code" for a real "token"
    protected function getTokenUrl()
    {
        return $this->baseUrl . '/oauth/token';
    }

    // How App 2 gets user details once it has a token
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get($this->baseUrl . '/api/user', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    // Map the App 1 user data to App 2's user object
    protected function mapUserToObject(array $user)
    {
        return (new User)->setRaw($user)->map([
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
        ]);
    }
}