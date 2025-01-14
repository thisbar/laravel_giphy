<?php

declare(strict_types=1);

namespace LaravelGhipy\Core\Gifs\Domain;

use LaravelGhipy\Shared\Domain\ValueObject\StringValueObject;

final class GifTitle extends StringValueObject
{
	public function __construct(string $value)
	{
		$this->ensureLengthIsBetweenRange($value, min: 1, max: 150);

		parent::__construct($value);
	}
}
