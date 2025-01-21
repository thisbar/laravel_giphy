<?php

declare(strict_types=1);

namespace LaravelGiphy\Core\Favorites\Infrastructure\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use LaravelGiphy\Core\Favorites\Domain\Favorite;
use LaravelGiphy\Core\Favorites\Domain\FavoriteAlias;
use LaravelGiphy\Core\Favorites\Domain\FavoriteRepository;
use LaravelGiphy\Shared\Domain\Favorites\FavoriteId;
use LaravelGiphy\Shared\Domain\Gifs\GifId;
use LaravelGiphy\Shared\Domain\Users\UserId;

final class DoctrineFavoriteRepository implements FavoriteRepository
{
	private EntityManagerInterface $em;

	public function __construct(EntityManagerInterface $em)
	{
		$this->em = $em;
	}

	public function search(FavoriteId $id): ?Favorite
	{
		return $this->em->getRepository(Favorite::class)->find($id);
	}

	public function searchByUserIdAndGifId(UserId $userId, GifId $gifId): ?Favorite
	{
		return $this->em->getRepository(Favorite::class)->findOneBy(['userId' => $userId, 'gifId' => $gifId]);
	}

	public function searchByUserIdAndAlias(UserId $userId, FavoriteAlias $alias): ?Favorite
	{
		return $this->em->getRepository(Favorite::class)->findOneBy(['userId' => $userId, 'alias' => $alias]);
	}

	public function save(Favorite $favorite): void
	{
		$this->em->persist($favorite);
		$this->em->flush();
	}
}
