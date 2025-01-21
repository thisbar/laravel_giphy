<?php

declare(strict_types=1);

namespace LaravelGiphy\Core\Favorites\Infrastructure\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use LaravelGiphy\Shared\Domain\Favorites\FavoriteId;

final class FavoriteIdType extends Type
{
	public const FAVORITE_ID = 'favorite_id';

	public function convertToPHPValue($value, AbstractPlatform $platform): FavoriteId
	{
		return FavoriteId::from((string) $value);
	}

	public function convertToDatabaseValue($value, AbstractPlatform $platform): string
	{
		return $value instanceof FavoriteId ? $value->value() : (string) $value;
	}

	public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
	{
		return 'CHAR(36)';
	}

	public function getName(): string
	{
		return self::FAVORITE_ID;
	}
}
