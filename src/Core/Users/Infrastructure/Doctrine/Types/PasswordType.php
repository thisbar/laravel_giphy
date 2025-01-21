<?php

declare(strict_types=1);

namespace LaravelGiphy\Core\Users\Infrastructure\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use InvalidArgumentException;
use LaravelGiphy\Core\Users\Domain\Password;

final class PasswordType extends Type
{
	public const NAME = 'password';

	public function convertToPHPValue($value, AbstractPlatform $platform): Password
	{
		return new Password((string) $value);
	}

	public function convertToDatabaseValue($value, AbstractPlatform $platform): string
	{
		if (!$value instanceof Password) {
			throw new InvalidArgumentException('Expected instance of Password.');
		}

		return $value->value();
	}

	public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
	{
		return 'VARCHAR(255)';
	}

	public function getName(): string
	{
		return self::NAME;
	}
}
