<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use LaravelGhipy\Core\Users\Domain\UserRepository;
use LaravelGhipy\Core\Users\Infrastructure\DoctrineUserRepository;

final class UserServiceProvider extends ServiceProvider
{
	public function register(): void
	{
		$this->app->bind(UserRepository::class, function (Application $app) {
			return new DoctrineUserRepository($app->make('Doctrine\ORM\EntityManagerInterface'));
		});
	}
}
