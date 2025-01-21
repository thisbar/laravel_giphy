<?php

declare(strict_types=1);

namespace LaravelGiphy\Core\Favorites\Infrastructure\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use InvalidArgumentException;
use LaravelGiphy\Core\Favorites\Domain\FavoriteAlias;

final class FavoriteAliasType extends Type
{
	public const FAVORITE_ALIAS = 'favorite_alias';

	public function convertToPHPValue($value, AbstractPlatform $platform): FavoriteAlias
	{
		return new FavoriteAlias((string) $value);
	}

	public function convertToDatabaseValue($value, AbstractPlatform $platform): string
	{
		if (!$value instanceof FavoriteAlias) {
			throw new InvalidArgumentException('Expected instance of FavoriteAlias.');
		}

		return $value->value();
	}

	public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
	{
		return 'VARCHAR(150)';
	}

	public function getName(): string
	{
		return self::FAVORITE_ALIAS;
	}
}
