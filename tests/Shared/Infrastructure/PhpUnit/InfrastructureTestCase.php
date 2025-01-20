<?php

declare(strict_types=1);

namespace LaravelGhipy\Tests\Shared\Infrastructure\PhpUnit;

use LaravelGhipy\Tests\TestCase;

abstract class InfrastructureTestCase extends TestCase
{
	protected function service(string $id): mixed
	{
		return $this->createApplication()->make($id);
	}
}
