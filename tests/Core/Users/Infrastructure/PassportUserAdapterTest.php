<?php

declare(strict_types=1);

namespace LaravelGhipy\Tests\Core\Users\Infrastructure;

use LaravelGhipy\Core\Users\Domain\Email;
use LaravelGhipy\Core\Users\Domain\Password;
use LaravelGhipy\Core\Users\Domain\User;
use LaravelGhipy\Core\Users\Infrastructure\PassportUserAdapter;
use LaravelGhipy\Shared\Domain\Users\UserId;
use PHPUnit\Framework\TestCase;

final class PassportUserAdapterTest extends TestCase
{
	private PassportUserAdapter $adapter;
	private User $user;

	protected function setUp(): void
	{
		parent::setUp();

		$userId   = UserId::random();
		$email    = new Email('test-user@test.com');
		$password = new Password('password123');

		$this->user    = new User($userId, $email, $password);
		$this->adapter = new PassportUserAdapter($this->user);
	}

	public function test_it_returns_auth_identifier_name(): void
	{
		// When
		$authIdentifierName = $this->adapter->getAuthIdentifierName();

		// Then
		$this->assertEquals('id', $authIdentifierName);
	}

	public function test_it_returns_auth_identifier(): void
	{
		// When
		$authIdentifier = $this->adapter->getAuthIdentifier();

		// Then
		$this->assertEquals($this->user->id(), $authIdentifier);
	}

	public function test_it_returns_auth_password(): void
	{
		// When
		$authPassword = $this->adapter->getAuthPassword();

		// Then
		$this->assertEquals($this->user->password(), $authPassword);
	}

	public function test_it_returns_null_for_remember_token(): void
	{
		// When
		$rememberToken = $this->adapter->getRememberToken();

		// Then
		$this->assertNull($rememberToken);
	}

	public function test_it_returns_null_for_remember_token_name(): void
	{
		// When
		$rememberTokenName = $this->adapter->getRememberTokenName();

		// Then
		$this->assertNull($rememberTokenName);
	}

	public function test_it_does_not_throw_error_on_set_remember_token(): void
	{
		// When
		$this->adapter->setRememberToken('some-token');

		// Then
		$this->assertTrue(true);
	}
}
