<?php

declare(strict_types=1);

namespace LaravelGhipy\Tests\Core\Shared\Infrastructure\Phpunit;

use Doctrine\ORM\EntityManager;
use LaravelGhipy\Tests\Shared\Infrastructure\PhpUnit\InfrastructureTestCase;

abstract class CoreInfrastructureTestCase extends InfrastructureTestCase
{
	protected function setUp(): void
	{
		parent::setUp();

		$arranger = new CoreEnvironmentArranger($this->service(EntityManager::class));

		$arranger->arrange();
	}

	protected function tearDown(): void
	{
		$arranger = new CoreEnvironmentArranger($this->service(EntityManager::class));

		$arranger->close();

		parent::tearDown();
	}
}
