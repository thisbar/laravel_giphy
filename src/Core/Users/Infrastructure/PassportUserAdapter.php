<?php

declare(strict_types=1);

namespace LaravelGhipy\Core\Users\Infrastructure;

use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use LaravelGhipy\Core\Users\Domain\User;

final class PassportUserAdapter implements Authenticatable
{
	public readonly User $user;
	private string $accessToken = '';
  
	public function __construct(User $user)
	{
		$this->user = $user;
	}

	public function getAuthIdentifierName(): string
	{
		return 'id';
	}

	public function getAuthIdentifier(): string
	{
		return $this->user->id();
	}

	public function getAuthPassword(): string
	{
		return $this->user->password();
	}

	public function getRememberToken(): null
	{
		return null;
	}

	public function __get(string $name)
	{
		if ($name === 'id') {
			return $this->getAuthIdentifier();
		}

		throw new Exception('Undefined property: ' . self::class . "::\$$name");
	}

	public function withAccessToken(string $token): self
	{
		$this->accessToken = $token;

		return $this;
	}

	public function getAccessToken(): ?string
	{
		return $this->accessToken;
	}

	public function setRememberToken($value) {}

	public function getRememberTokenName(): null
	{
		return null;
	}
}
