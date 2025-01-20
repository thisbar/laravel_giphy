<?php

declare(strict_types=1);

namespace LaravelGhipy\Tests\Core\Favorites\Application;

use LaravelGhipy\Core\Favorites\Application\FavoriteSaver;
use LaravelGhipy\Core\Favorites\Domain\Favorite;
use LaravelGhipy\Core\Favorites\Domain\FavoriteAlias;
use LaravelGhipy\Core\Favorites\Domain\FavoriteAliasAlreadyExists;
use LaravelGhipy\Core\Favorites\Domain\FavoriteAlreadyExists;
use LaravelGhipy\Core\Favorites\Domain\FavoriteRepository;
use LaravelGhipy\Shared\Domain\Gifs\GifId;
use LaravelGhipy\Shared\Domain\Users\UserId;
use LaravelGhipy\Tests\Shared\Infrastructure\PhpUnit\UnitTestCase;
use Mockery;
use Mockery\MockInterface;

final class FavoriteSaverTest extends UnitTestCase
{
	private FavoriteRepository | MockInterface | null $repository;

	protected function tearDown(): void
	{
		Mockery::close();
		parent::tearDown();
	}

	public function test_it_saves_a_favorite_successfully(): void
	{
		$gifId    = GifId::from('test-id');
		$alias    = FavoriteAlias::from('test alias');
		$userId   = UserId::random();
		$favorite = Favorite::create($gifId, $alias, $userId);

		$this->repository()
			->shouldReceive('searchByUserIdAndGifId')
			->once()
			->with($userId, $gifId)
			->andReturn(null);

		$this->repository()
			->shouldReceive('searchByUserIdAndAlias')
			->once()
			->with($userId, $alias)
			->andReturn(null);

		$this->repository()
			->shouldReceive('save')
			->once()
			->with(Mockery::type(Favorite::class));

		$favoriteSaver = new FavoriteSaver($this->repository());
		$favoriteSaver->save($favorite);
	}

	public function test_it_throws_an_exception_when_favorite_with_same_gif_id_and_user_id_exists(): void
	{
		$gifId            = GifId::from('test-id');
		$alias            = FavoriteAlias::from('test alias');
		$userId           = UserId::random();
		$existingFavorite = Favorite::create($gifId, $alias, $userId);

		$this->repository()
			->shouldReceive('searchByUserIdAndGifId')
			->once()
			->with($userId, $gifId)
			->andReturn($existingFavorite);

		$this->repository()
			->shouldNotReceive('searchByUserIdAndAlias');
		$this->repository()
			->shouldNotReceive('save');

		$favoriteSaver = new FavoriteSaver($this->repository());

		$this->expectException(FavoriteAlreadyExists::class);

		$favoriteSaver->save(Favorite::create($gifId, $alias, $userId));
	}

	public function test_it_throws_an_exception_when_favorite_with_same_alias_and_user_id_exists(): void
	{
		$this->expectException(FavoriteAliasAlreadyExists::class);

		$gifId            = GifId::from('test-id');
		$alias            = FavoriteAlias::from('test alias');
		$userId           = UserId::random();
		$existingFavorite = Favorite::create($gifId, $alias, $userId);

		$this->repository()
			->shouldReceive('searchByUserIdAndGifId')
			->once()
			->with($userId, $gifId)
			->andReturn(null);

		$this->repository()
			->shouldReceive('searchByUserIdAndAlias')
			->once()
			->with($userId, $alias)
			->andReturn($existingFavorite);

		$this->repository()
			->shouldNotReceive('save');

		$favoriteSaver = new FavoriteSaver($this->repository());

		$favoriteSaver->save(Favorite::create($gifId, $alias, $userId));
	}

	private function repository(): FavoriteRepository | MockInterface
	{
		return $this->repository ??= $this->mock(FavoriteRepository::class);
	}
}
