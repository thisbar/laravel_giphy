<?php

declare(strict_types=1);

namespace LaravelGhipy\Tests\Shared\Infrastructure\PhpUnit;

use InvalidArgumentException;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\MockInterface;

abstract class UnitTestCase extends MockeryTestCase
{
	protected function mock(string $className): MockInterface
	{
		if (class_exists($className)) {
			return Mockery::mock($className);
		}

		throw new InvalidArgumentException(sprintf('Invalid classname <%s>', $className));
	}
}
