<?php

declare(strict_types=1);

namespace LaravelGhipy\Core\Favorites\Application;

use LaravelGhipy\Core\Favorites\Domain\Favorite;
use LaravelGhipy\Core\Favorites\Domain\FavoriteRepository;
use LaravelGhipy\Shared\Domain\Favorites\FavoriteId;

final class FavoriteSearcher
{
	public function __construct(private readonly FavoriteRepository $repository) {}

	public function search(FavoriteId $id): ?Favorite
	{
		return $this->repository->search($id);
	}
}
