<?php

declare(strict_types=1);

namespace LaravelGhipy\Shared\Domain\ValueObject;

final class Count extends IntValueObject
{
	public function __construct(int $value)
	{
		$this->ensureValueIsAboveMinimum(value: $value, min: 0);

		parent::__construct($value);
	}
}
