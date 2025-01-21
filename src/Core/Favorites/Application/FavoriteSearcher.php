<?php

declare(strict_types=1);

namespace LaravelGiphy\Core\Favorites\Application;

use LaravelGiphy\Core\Favorites\Domain\Favorite;
use LaravelGiphy\Core\Favorites\Domain\FavoriteRepository;
use LaravelGiphy\Shared\Domain\Favorites\FavoriteId;

final class FavoriteSearcher
{
	public function __construct(private readonly FavoriteRepository $repository) {}

	public function search(FavoriteId $id): ?Favorite
	{
		return $this->repository->search($id);
	}
}
