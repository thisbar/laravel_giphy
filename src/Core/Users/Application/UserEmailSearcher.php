<?php

declare(strict_types=1);

namespace LaravelGiphy\Core\Users\Application;

use LaravelGiphy\Core\Users\Domain\Email;
use LaravelGiphy\Core\Users\Domain\User;
use LaravelGiphy\Core\Users\Domain\UserRepository;

final class UserEmailSearcher
{
	public function __construct(private UserRepository $repository) {}

	public function search(Email $email): ?User
	{
		return $this->repository->searchByEmail($email);
	}
}
