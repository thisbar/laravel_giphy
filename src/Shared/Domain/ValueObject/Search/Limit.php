<?php

declare(strict_types=1);

namespace LaravelGhipy\Shared\Domain\ValueObject\Search;

use LaravelGhipy\Shared\Domain\ValueObject\IntValueObject;

final class Limit extends IntValueObject
{
	public const DEFAULT_VALUE = 25;

	public function __construct(?int $value = self::DEFAULT_VALUE)
	{
		$value ??= self::DEFAULT_VALUE;
		$this->ensureValueIsBetweenRange(value: $value, min: 1, max: 50);

		parent::__construct($value);
	}
}
