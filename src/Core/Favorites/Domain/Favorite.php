<?php

declare(strict_types=1);

namespace LaravelGiphy\Core\Favorites\Domain;

use LaravelGiphy\Shared\Domain\Favorites\FavoriteId;
use LaravelGiphy\Shared\Domain\Gifs\GifId;
use LaravelGiphy\Shared\Domain\Users\UserId;

final class Favorite
{
	public function __construct(
		private FavoriteId $id,
		private GifId $gifId,
		private FavoriteAlias $alias,
		private UserId $userId,
	) {}

	public static function create(GifId $gifId, FavoriteAlias $alias, UserId $userId): self
	{
		return new self(FavoriteId::random(), $gifId, $alias, $userId);
	}

	public function id(): FavoriteId
	{
		return $this->id;
	}

	public function gifId(): GifId
	{
		return $this->gifId;
	}

	public function alias(): FavoriteAlias
	{
		return $this->alias;
	}

	public function userId(): UserId
	{
		return $this->userId;
	}

	public function toArray(): array
	{
		return [
			'id'      => $this->id->value(),
			'alias'   => $this->alias->value(),
			'gif_id'  => $this->gifId->value(),
			'user_id' => $this->userId->value(),
		];
	}
}
