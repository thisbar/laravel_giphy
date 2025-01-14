<?php

declare(strict_types=1);

namespace LaravelGhipy\Shared\Domain\ValueObject\Search;

use LaravelGhipy\Shared\Domain\ValueObject\StringValueObject;

final class MetadataMessage extends StringValueObject
{
	public function __construct(string $value = '')
	{
		parent::ensureLengthIsBetweenRange(value: $value, min: 1, max: 100);

		parent::__construct($value);
	}
}
