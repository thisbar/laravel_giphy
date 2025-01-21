<?php

declare(strict_types=1);

namespace LaravelGiphy\Shared\Domain\ValueObject;

use InvalidArgumentException;

abstract class IntValueObject
{
	public function __construct(protected readonly int $value) {}

	abstract public static function from(int $value): static;

	public function value(): int
	{
		return $this->value;
	}

	public function __toString(): string
	{
		return (string) $this->value;
	}

	protected function ensureValueIsAboveMinimum(int $value, int $min): void
	{
		if ($value < $min) {
			throw new InvalidArgumentException(sprintf(
				'The integer must be above or equal <%d>. Value: <%d>',
				$min,
				$value
			));
		}
	}

	protected function ensureValueIsBetweenRange(int $value, int $min, int $max): void
	{
		if ($value < $min || $value > $max) {
			throw new InvalidArgumentException(sprintf(
				'The integer must be between <%d> and <%d>. Value: <%d>',
				$min,
				$max,
				$value
			));
		}
	}
}
