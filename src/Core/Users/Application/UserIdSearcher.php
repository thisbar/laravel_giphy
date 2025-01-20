<?php

declare(strict_types=1);

namespace LaravelGhipy\Core\Users\Application;

use LaravelGhipy\Core\Users\Domain\User;
use LaravelGhipy\Core\Users\Domain\UserRepository;
use LaravelGhipy\Shared\Domain\Users\UserId;

final class UserIdSearcher
{
	public function __construct(private UserRepository $repository) {}

	public function search(UserId $id): ?User
	{
		return $this->repository->search($id);
	}
}
