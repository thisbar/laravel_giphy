<?php

declare(strict_types=1);

namespace LaravelGhipy\Tests\Core\Shared\Infrastructure\Phpunit;

use Database\Seeders\UserSeeder;
use Doctrine\ORM\EntityManager;
use LaravelGhipy\Tests\Shared\Infrastructure\Arranger\EnvironmentArranger;
use LaravelGhipy\Tests\Shared\Infrastructure\Doctrine\MySqlDatabaseCleaner;

use function Lambdish\Phunctional\apply;

final readonly class CoreEnvironmentArranger implements EnvironmentArranger
{
	public function __construct(private EntityManager $entityManager) {}

	public function arrange(): void
	{
		apply(new MySqlDatabaseCleaner(), [$this->entityManager]);
	}

	public function close(): void
	{
		apply(new UserSeeder($this->entityManager));
	}
}
