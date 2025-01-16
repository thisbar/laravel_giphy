<?php

declare(strict_types=1);

namespace LaravelGhipy\Shared\Domain\ValueObject;

use InvalidArgumentException;

abstract class StringValueObject
{
	public function __construct(protected readonly string $value) {}

	abstract public static function from(string $value): static;

	public function value(): string
	{
		return $this->value;
	}

	public function __toString(): string
	{
		return $this->value();
	}

	protected function ensureLengthIsBetweenRange(string $value, int $min, int $max): void
	{
		$valueLength = strlen($value);

		if ($valueLength < $min || $valueLength > $max) {
			throw new InvalidArgumentException(sprintf(
				'The value length must have between <%d> and <%d> characters. Value: <%s>',
				$min,
				$max,
				$value
			));
		}
	}
}
