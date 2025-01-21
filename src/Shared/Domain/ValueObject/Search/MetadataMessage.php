<?php

declare(strict_types=1);

namespace LaravelGiphy\Shared\Domain\ValueObject\Search;

use LaravelGiphy\Shared\Domain\ValueObject\StringValueObject;

final class MetadataMessage extends StringValueObject
{
	public function __construct(string $value = '')
	{
		parent::ensureLengthIsBetweenRange(value: $value, min: 1, max: 100);

		parent::__construct($value);
	}

	public static function from(string $value): static
	{
		return new self($value);
	}
}
