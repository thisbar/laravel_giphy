<?php

declare(strict_types=1);

namespace LaravelGhipy\Shared\Domain\Favorites;

use LaravelGhipy\Shared\Domain\ValueObject\Uuid;

final class FavoriteId extends Uuid
{
	public static function from(string $value): static
	{
		return new self($value);
	}
}
