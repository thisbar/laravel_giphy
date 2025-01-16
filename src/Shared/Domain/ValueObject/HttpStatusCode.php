<?php

declare(strict_types=1);

namespace LaravelGhipy\Shared\Domain\ValueObject;

final class HttpStatusCode extends IntValueObject
{
	public function __construct(int $value)
	{
		$this->ensureValueIsBetweenRange(value: $value, min: 100, max: 511);

		parent::__construct($value);
	}


	public static function from(int $value): static
	{
		return new self($value);
	}
}
