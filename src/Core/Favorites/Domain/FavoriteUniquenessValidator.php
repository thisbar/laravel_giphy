<?php

declare(strict_types=1);

namespace LaravelGhipy\Core\Favorites\Domain;

use LaravelGhipy\Shared\Domain\Gifs\GifId;
use LaravelGhipy\Shared\Domain\Users\UserId;

final class FavoriteUniquenessValidator
{
	public function __construct(private readonly FavoriteRepository $repository) {}

	public function validate(UserId $userId, GifId $gifId): void
	{
		$favorite = $this->repository->searchByUserIdAndGifId($userId, $gifId);

		if ($favorite !== null) {
			throw new FavoriteAlreadyExists();
		}
	}
}
