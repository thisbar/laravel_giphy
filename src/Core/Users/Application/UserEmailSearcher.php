<?php

declare(strict_types=1);

namespace LaravelGhipy\Core\Users\Application;

use LaravelGhipy\Core\Users\Domain\Email;
use LaravelGhipy\Core\Users\Domain\User;
use LaravelGhipy\Core\Users\Domain\UserRepository;

final class UserEmailSearcher
{
	public function __construct(private UserRepository $repository) {}

	public function search(Email $email): ?User
	{
		return $this->repository->searchByEmail($email);
	}
}
