<?php

namespace App\Providers;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Token\Parser;

class JwtServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Configuration::class, function () {
            /** @var non-empty-string $privateKeyPath */
            $privateKeyPath = storage_path('oauth-private.key');
            /** @var non-empty-string $publicKeyPath */
            $publicKeyPath = storage_path('oauth-public.key');

            if (!is_file($privateKeyPath)) {
                throw new \RuntimeException('Private key file not found.');
            }

            if (!is_file($publicKeyPath)) {
                throw new \RuntimeException('Public key file not found.');
            }



            return Configuration::forAsymmetricSigner(
                new Sha256(),
                InMemory::file($privateKeyPath),
                InMemory::file($publicKeyPath)
            );
        });

        $this->app->bind(Parser::class, function (Application $app): \Lcobucci\JWT\Parser {
            return $app->make(Configuration::class)->parser();
        });
    }

    public function boot(): void
    {
    }
}
