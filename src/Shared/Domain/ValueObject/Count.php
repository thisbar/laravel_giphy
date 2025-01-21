<?php

declare(strict_types=1);

namespace LaravelGiphy\Shared\Domain\ValueObject;

final class Count extends IntValueObject
{
	public function __construct(int $value)
	{
		$this->ensureValueIsAboveMinimum(value: $value, min: 0);

		parent::__construct($value);
	}

	public static function from(int $value): static
	{
		return new self($value);
	}
}
