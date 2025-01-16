<?php

declare(strict_types=1);

namespace LaravelGhipy\Shared\Domain\Gifs;

use LaravelGhipy\Shared\Domain\ValueObject\StringValueObject;

final class GifId extends StringValueObject
{
	public static function from(string $value): static
	{
		return new self($value);
	}
}
