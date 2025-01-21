<?php

declare(strict_types=1);

namespace LaravelGiphy\Core\Favorites\Domain;

use LaravelGiphy\Shared\Domain\DomainError;

final class FavoriteAliasAlreadyExists extends DomainError
{
	public const HTTP_CONFLICT = 409;
	public function __construct(private readonly FavoriteAlias $alias)
	{
		parent::__construct();
	}

	public function errorCode(): int
	{
		return self::HTTP_CONFLICT;
	}

	protected function errorMessage(): string
	{
		return sprintf('The alias <%s> is already in use for another favorite.', $this->alias->value());
	}
}
