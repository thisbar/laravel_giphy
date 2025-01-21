<?php

declare(strict_types=1);

namespace LaravelGiphy\Core\Favorites\Domain;

use LaravelGiphy\Shared\Domain\Favorites\FavoriteId;
use LaravelGiphy\Shared\Domain\Gifs\GifId;
use LaravelGiphy\Shared\Domain\Users\UserId;

interface FavoriteRepository
{
	public function search(FavoriteId $id): ?Favorite;
	public function searchByUserIdAndGifId(UserId $userId, GifId $gifId): ?Favorite;
	public function searchByUserIdAndAlias(UserId $userId, FavoriteAlias $alias): ?Favorite;
	public function save(Favorite $favorite): void;
}
