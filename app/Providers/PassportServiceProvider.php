<?php

namespace App\Providers;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Passport;
use Laravel\Passport\PersonalAccessTokenFactory;
use Laravel\Passport\TokenRepository;
use Lcobucci\JWT\Parser as ParserInterface;
use Lcobucci\JWT\Token\Parser;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;

final class PassportServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ClientRepositoryInterface::class, ClientRepository::class);
        $this->app->bind(ParserInterface::class, Parser::class);

        $this->app->singleton(PersonalAccessTokenFactory::class, function (Application $app) {
            return new PersonalAccessTokenFactory(
                $app->make(AuthorizationServer::class),
                $app->make(ClientRepository::class),
                $app->make(TokenRepository::class),
                $app->make(Parser::class)
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Passport::personalAccessTokensExpireIn(now()->addMinutes(config('passport.tokens_expire_in_minutes')));
        Passport::tokensExpireIn(now()->addMinutes(config('passport.tokens_expire_in_minutes')));
        Passport::refreshTokensExpireIn(now()->addDays(config('passport.refresh_tokens_expire_in_days')));
    }
}

