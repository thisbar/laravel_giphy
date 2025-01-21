<?php

declare(strict_types=1);

namespace LaravelGiphy\Core\Users\Infrastructure;

use Doctrine\ORM\EntityManagerInterface;
use LaravelGiphy\Core\Users\Domain\Email;
use LaravelGiphy\Core\Users\Domain\User;
use LaravelGiphy\Core\Users\Domain\UserRepository;
use LaravelGiphy\Shared\Domain\Users\UserId;

final class DoctrineUserRepository implements UserRepository
{
	private EntityManagerInterface $em;

	public function __construct(EntityManagerInterface $em)
	{
		$this->em = $em;
	}

	public function search(UserId $id): ?User
	{
		return $this->em->getRepository(User::class)->find($id);
	}

	public function searchByEmail(Email $email): ?User
	{
		return $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
	}

	public function save(User $user): void
	{
		$this->em->persist($user);
		$this->em->flush();
	}
}
