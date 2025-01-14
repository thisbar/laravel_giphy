<?php

declare(strict_types=1);

namespace LaravelGhipy\Tests\Shared\Infrastructure\PhpUnit;

use InvalidArgumentException;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\MockInterface;

abstract class UnitTestCase extends MockeryTestCase
{
	protected function mock(string $interface): MockInterface
	{
		if (interface_exists($interface)) {
			return Mockery::mock($interface);
		}

		throw new InvalidArgumentException(sprintf('Invalid interface <%s>', $interface));
	}
}
