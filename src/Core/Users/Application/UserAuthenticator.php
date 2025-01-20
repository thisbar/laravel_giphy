<?php

declare(strict_types=1);

namespace LaravelGhipy\Core\Users\Application;

use LaravelGhipy\Core\Users\Domain\Email;
use LaravelGhipy\Core\Users\Domain\User;
use LaravelGhipy\Core\Users\Domain\UserRepository;

final class UserAuthenticator
{
	private UserRepository $userRepository;

	public function __construct(UserRepository $userRepository)
	{
		$this->userRepository = $userRepository;
	}

	public function auth(Email $email, string $password): ?User
	{
		$user = $this->userRepository->searchByEmail($email);

		if (!$user || !$user->verifyPassword($password)) {
			return null;
		}

		return $user;
	}
}
