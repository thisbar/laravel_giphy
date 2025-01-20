<?php

namespace App\Providers;

use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use LaravelGhipy\Core\Users\Infrastructure\PassportDoctrineUserProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Auth::provider('doctrine', function (Application $app) {
            return new PassportDoctrineUserProvider(
                $app->make(EntityManagerInterface::class)
            );
        });
    }
}
