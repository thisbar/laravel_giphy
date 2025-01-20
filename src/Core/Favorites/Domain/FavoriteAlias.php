<?php

declare(strict_types=1);

namespace LaravelGhipy\Core\Favorites\Domain;

use LaravelGhipy\Shared\Domain\ValueObject\StringValueObject;

final class FavoriteAlias extends StringValueObject
{
	public function __construct(string $value)
	{
		$this->ensureLengthIsBetweenRange($value, min: 1, max: 150);

		parent::__construct($value);
	}

	public static function from(string $value): static
	{
		return new self($value);
	}
}
