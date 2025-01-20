<?php

declare(strict_types=1);

namespace LaravelGhipy\Core\Users\Domain;

use LaravelGhipy\Shared\Domain\Users\UserId;

final class User
{
	public function __construct(
		private UserId $id,
		private Email $email,
		private Password $password
	) {}

	public static function create(Email $email, Password $password): self
	{
		return new self(UserId::random(), $email, $password);
	}

	public function id(): string
	{
		return $this->id->value();
	}

	public function verifyPassword(string $password): bool
	{
		return $this->password->verify($password);
	}

	public function email(): string
	{
		return $this->email->value();
	}

	public function password(): string
	{
		return $this->password->value();
	}
}
