<?php

declare(strict_types=1);

namespace LaravelGiphy\Core\Users\Domain;

use InvalidArgumentException;
use LaravelGiphy\Shared\Domain\ValueObject\StringValueObject;

final class Password extends StringValueObject
{
	public const MIN_PASSWORD_LENGTH = 8;

	public function __construct(string $hashedPassword)
	{
		parent::__construct($hashedPassword);
	}

	public static function from(string $value): static
	{
		return new self(self::hashPassword($value));
	}

	public function verify(string $password): bool
	{
		return password_verify($password, $this->value());
	}

	private static function hashPassword(string $password): string
	{
		if (strlen($password) < self::MIN_PASSWORD_LENGTH) {
			throw new InvalidArgumentException(
				sprintf('Password must be at least <%d> characters long.', (string) self::MIN_PASSWORD_LENGTH)
			);
		}

		return password_hash($password, PASSWORD_BCRYPT);
	}
}
