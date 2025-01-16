<?php

declare(strict_types=1);

namespace LaravelGhipy\Shared\Domain\ValueObject\Search;

use LaravelGhipy\Shared\Domain\ValueObject\IntValueObject;

final class Offset extends IntValueObject
{
	public const DEFAULT_VALUE = 25;

	public function __construct(?int $value = self::DEFAULT_VALUE)
	{
		$value ??= self::DEFAULT_VALUE;
		$this->ensureValueIsBetweenRange(value: $value, min: 0, max: 4999);

		parent::__construct($value);
	}

	public static function from(int $value): static
	{
		return new self($value);
	}
}
