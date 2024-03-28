<?php

namespace App\Providers;

use App\GrantTypes\UserIdGrantType;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Bridge\RefreshTokenRepository;
use Laravel\Passport\Passport;
use League\OAuth2\Server\AuthorizationServer;

/**
 * Class PassportServiceProvider
 */
class PassportServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->resolving(AuthorizationServer::class, function (AuthorizationServer $server) {
            $server->enableGrantType($this->makeUserIdGrant(), Passport::tokensExpireIn());
        });
    }

    /**
     * @return UserIdGrantType
     * @throws BindingResolutionException
     */
    protected function makeUserIdGrant(): UserIdGrantType
    {
        $grant = new UserIdGrantType($this->app->make(RefreshTokenRepository::class));

        $grant->setRefreshTokenTTL(Passport::refreshTokensExpireIn());

        return $grant;
    }
}
