<?php

declare(strict_types=1);

namespace LaravelGiphy\Core\Users\Application;

use LaravelGiphy\Core\Users\Domain\User;
use LaravelGiphy\Core\Users\Domain\UserRepository;
use LaravelGiphy\Shared\Domain\Users\UserId;

final class UserIdSearcher
{
	public function __construct(private UserRepository $repository) {}

	public function search(UserId $id): ?User
	{
		return $this->repository->search($id);
	}
}
