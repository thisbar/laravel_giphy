<?php

declare(strict_types=1);

namespace LaravelGiphy\Core\Users\Infrastructure\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use InvalidArgumentException;
use LaravelGiphy\Core\Users\Domain\Email;

final class EmailType extends Type
{
	public const EMAIL = 'email';

	public function convertToPHPValue($value, AbstractPlatform $platform): Email
	{
		return new Email((string) $value);
	}

	public function convertToDatabaseValue($value, AbstractPlatform $platform): string
	{
		if (!$value instanceof Email) {
			throw new InvalidArgumentException('Expected instance of Email.');
		}

		return $value->value();
	}

	public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
	{
		return 'VARCHAR(255)';
	}

	public function getName(): string
	{
		return self::EMAIL;
	}
}
