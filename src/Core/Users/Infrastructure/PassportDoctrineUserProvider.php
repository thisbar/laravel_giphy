<?php

declare(strict_types=1);

namespace LaravelGiphy\Core\Users\Infrastructure;

use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use LaravelGiphy\Core\Users\Domain\User;

final class PassportDoctrineUserProvider implements UserProvider
{
	private EntityManagerInterface $entityManager;

	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	public function retrieveById($identifier): ?PassportUserAdapter
	{
		$user = $this->entityManager->find(User::class, $identifier);
		return $user ? new PassportUserAdapter($user) : null;
	}

	public function retrieveByCredentials(array $credentials): ?PassportUserAdapter
	{
		$repository = $this->entityManager->getRepository(User::class);
		$user       = $repository->findOneBy(['email' => $credentials['email']]);
		return $user ? new PassportUserAdapter($user) : null;
	}

	public function validateCredentials(Authenticatable $user, array $credentials): bool
	{
		return password_verify($credentials['password'], $user->getAuthPassword());
	}

	public function retrieveByToken($identifier, $token) {}

	public function updateRememberToken(Authenticatable $user, $token) {}
}
