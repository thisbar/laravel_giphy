<?php

declare(strict_types=1);

namespace LaravelGiphy\Core\Favorites\Domain;

use LaravelGiphy\Shared\Domain\Gifs\GifId;
use LaravelGiphy\Shared\Domain\Users\UserId;

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
