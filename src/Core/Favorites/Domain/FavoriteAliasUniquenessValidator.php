<?php

declare(strict_types=1);

namespace LaravelGiphy\Core\Favorites\Domain;

use LaravelGiphy\Shared\Domain\Users\UserId;

final class FavoriteAliasUniquenessValidator
{
	public function __construct(private readonly FavoriteRepository $repository) {}

	public function validate(UserId $userId, FavoriteAlias $alias): void
	{
		$favorite = $this->repository->searchByUserIdAndAlias($userId, $alias);

		if ($favorite !== null) {
			throw new FavoriteAliasAlreadyExists($alias);
		}
	}
}
