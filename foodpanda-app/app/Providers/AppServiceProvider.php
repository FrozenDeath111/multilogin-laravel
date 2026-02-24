<?php

namespace App\Providers;

use App\Socialite\EcomProvider;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use Laravel\Socialite\Facades\Socialite;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Passport::tokensExpireIn(now()->addDays(1));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));

        Socialite::extend('ecom_sso', function ($app) {
            $config = $app['config']['services.ecom_sso'];
            return Socialite::buildProvider(EcomProvider::class, $config);
        });
    }
}
