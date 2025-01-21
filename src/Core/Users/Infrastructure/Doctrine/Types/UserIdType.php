<?php

declare(strict_types=1);

namespace LaravelGiphy\Core\Users\Infrastructure\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use LaravelGiphy\Shared\Domain\Users\UserId;

final class UserIdType extends Type
{
	public const USER_ID = 'user_id';

	public function convertToPHPValue($value, AbstractPlatform $platform): UserId
	{
		return new UserId((string) $value);
	}

	public function convertToDatabaseValue($value, AbstractPlatform $platform): string
	{
		return $value instanceof UserId ? $value->value() : (string) $value;
	}

	public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
	{
		return 'CHAR(36)';
	}

	public function getName(): string
	{
		return self::USER_ID;
	}
}
