<?php

declare(strict_types=1);

namespace LaravelGhipy\Core\Favorites\Domain;

use LaravelGhipy\Shared\Domain\Favorites\FavoriteId;
use LaravelGhipy\Shared\Domain\Gifs\GifId;
use LaravelGhipy\Shared\Domain\Users\UserId;

interface FavoriteRepository
{
	public function search(FavoriteId $id): ?Favorite;
	public function searchByUserIdAndGifId(UserId $userId, GifId $gifId): ?Favorite;
	public function searchByUserIdAndAlias(UserId $userId, FavoriteAlias $alias): ?Favorite;
	public function save(Favorite $favorite): void;
}
