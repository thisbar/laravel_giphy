<?php

declare(strict_types=1);

namespace LaravelGhipy\Tests\Core\Favorites\Infrastructure;

use Doctrine\ORM\EntityManagerInterface;
use LaravelGhipy\Core\Favorites\Domain\Favorite;
use LaravelGhipy\Core\Favorites\Domain\FavoriteAlias;
use LaravelGhipy\Core\Favorites\Infrastructure\Doctrine\DoctrineFavoriteRepository;
use LaravelGhipy\Shared\Domain\Favorites\FavoriteId;
use LaravelGhipy\Shared\Domain\Gifs\GifId;
use LaravelGhipy\Shared\Domain\Users\UserId;
use LaravelGhipy\Tests\Core\Shared\Infrastructure\Phpunit\CoreInfrastructureTestCase;

final class DoctrineFavoriteRepositoryTest extends CoreInfrastructureTestCase
{
	private DoctrineFavoriteRepository $repository;
	private EntityManagerInterface $entityManager;

	protected function setUp(): void
	{
		parent::setUp();

		$this->entityManager = $this->service(EntityManagerInterface::class);
		$this->repository    = $this->service(DoctrineFavoriteRepository::class);
	}

	public function test_it_returns_favorite_when_found(): void
	{
		// Given
		$favoriteId       = FavoriteId::random();
		$gifId            = GifId::from('gif-id-123');
		$alias            = FavoriteAlias::from('My Favorite Gif');
		$userId           = UserId::random();
		$expectedFavorite = new Favorite($favoriteId, $gifId, $alias, $userId);
		$this->repository->save($expectedFavorite);

		// When
		$foundFavorite = $this->repository->search($favoriteId);

		// Then
		$this->assertEquals($expectedFavorite, $foundFavorite);
	}

	public function test_it_returns_null_when_favorite_not_found(): void
	{
		// Given
		$favoriteId = FavoriteId::random();
		// When
		$nullFavorite = $this->repository->search($favoriteId);
		// Then
		$this->assertNull($nullFavorite);
	}

	public function test_it_finds_favorite_by_user_id_and_gif_id(): void
	{
		// Given
		$gifId    = GifId::from('gif-id-123');
		$alias    = FavoriteAlias::from('My Favorite Gif');
		$userId   = UserId::random();
		$favorite = Favorite::create($gifId, $alias, $userId);
		$this->entityManager->persist($favorite);
		$this->entityManager->flush();

		// When
		$foundFavorite = $this->repository->searchByUserIdAndGifId($userId, $gifId);

		// Then
		$this->assertEquals($favorite, $foundFavorite);
	}

	public function test_it_returns_null_when_favorite_by_user_id_and_gif_id_not_found(): void
	{
		// Given
		$gifId  = GifId::from('non-existent-gif');
		$userId = UserId::random();

		// When
		$nullFavorite = $this->repository->searchByUserIdAndGifId($userId, $gifId);

		// Then
		$this->assertNull($nullFavorite);
	}

	public function test_it_finds_favorite_by_user_id_and_alias(): void
	{
		// Given
		$gifId    = GifId::from('gif-id-123');
		$alias    = FavoriteAlias::from('My Favorite Gif');
		$userId   = UserId::random();
		$favorite = Favorite::create($gifId, $alias, $userId);

		$this->entityManager->persist($favorite);
		$this->entityManager->flush();

		// When
		$foundFavorite = $this->repository->searchByUserIdAndAlias($userId, $alias);

		// Then
		$this->assertEquals($favorite, $foundFavorite);
	}

	public function test_it_returns_null_when_favorite_by_user_id_and_alias_not_found(): void
	{
		// Given
		$alias  = FavoriteAlias::from('Non-existent alias');
		$userId = UserId::random();
		// When
		$nullFavorite = $this->repository->searchByUserIdAndAlias($userId, $alias);
		// Then
		$this->assertNull($nullFavorite);
	}
}
