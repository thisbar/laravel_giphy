<?php

declare(strict_types=1);

namespace LaravelGhipy\Core\Gifs\Infrastructure\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use LaravelGhipy\Shared\Domain\Gifs\GifId;

final class GifIdType extends Type
{
	public const GIF_ID = 'GIF_ID';

	public function convertToPHPValue($value, AbstractPlatform $platform): GifId
	{
		return new GifId((string) $value);
	}

	public function convertToDatabaseValue($value, AbstractPlatform $platform): string
	{
		return $value instanceof GifId ? $value->value() : (string) $value;
	}

	public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
	{
		return 'CHAR(36)';
	}

	public function getName(): string
	{
		return self::GIF_ID;
	}
}
