<?php

declare(strict_types=1);

namespace LaravelGiphy\Shared\Domain\Gifs;

use LaravelGiphy\Shared\Domain\ValueObject\StringValueObject;

final class GifId extends StringValueObject
{
	public static function from(string $value): static
	{
		return new self($value);
	}
}
