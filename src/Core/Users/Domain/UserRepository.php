<?php

declare(strict_types=1);

namespace LaravelGiphy\Core\Users\Domain;

use LaravelGiphy\Shared\Domain\Users\UserId;

interface UserRepository
{
	public function search(UserId $id): ?User;
	public function searchByEmail(Email $email): ?User;
	public function save(User $user): void;
}
