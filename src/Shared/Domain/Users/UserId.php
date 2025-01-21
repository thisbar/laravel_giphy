<?php

declare(strict_types=1);

namespace LaravelGiphy\Shared\Domain\Users;

use LaravelGiphy\Shared\Domain\ValueObject\Uuid;

final class UserId extends Uuid
{
	public static function from(string $value): static
	{
		return new self($value);
	}
}
