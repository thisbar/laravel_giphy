<?php

declare(strict_types=1);

namespace LaravelGhipy\Shared\Domain\ValueObject\Search;

use LaravelGhipy\Shared\Domain\ValueObject\StringValueObject;

final class SearchQuery extends StringValueObject
{
	public function __construct(string $value = '')
	{
		parent::ensureLengthIsBetweenRange(value: $value, min: 1, max: 50);

		parent::__construct($value);
	}
}
