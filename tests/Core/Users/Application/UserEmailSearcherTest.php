<?php

declare(strict_types=1);

namespace LaravelGhipy\Tests\Core\Users\Application;

use LaravelGhipy\Core\Users\Application\UserEmailSearcher;
use LaravelGhipy\Core\Users\Domain\Email;
use LaravelGhipy\Core\Users\Domain\Password;
use LaravelGhipy\Core\Users\Domain\User;
use LaravelGhipy\Core\Users\Domain\UserRepository;
use LaravelGhipy\Tests\Shared\Infrastructure\PhpUnit\UnitTestCase;
use Mockery;
use Mockery\MockInterface;

final class UserEmailSearcherTest extends UnitTestCase
{
	private MockInterface | UserRepository | null $repository;

	protected function tearDown(): void
	{
		Mockery::close();
		parent::tearDown();
	}

	public function test_it_returns_user_when_found(): void
	{
		// Given
		$email        = new Email('test@test.com');
		$password     = Password::from('password123');
		$existingUser = User::create($email, $password);

		$this->repository()
			->shouldReceive('searchByEmail')
			->once()
			->with($email)
			->andReturn($existingUser);

		// When
		$userEmailSearcher = new UserEmailSearcher($this->repository);
		$userResult        = $userEmailSearcher->search($email);

		// Then
		$this->assertSame($userResult, $existingUser);
	}

	public function test_it_returns_null_when_user_not_found(): void
	{
		// Given
		$email = Email::from('non.existing@email.com');

		$this->repository()
			->shouldReceive('searchByEmail')
			->once()
			->with($email)
			->andReturn(null);

		// When
		$userEmailSearcher = new UserEmailSearcher($this->repository);
		$userResult        = $userEmailSearcher->search($email);

		// Then
		$this->assertNull($userResult);
	}

	private function repository(): MockInterface | UserRepository
	{
		return $this->repository ??= $this->mock(UserRepository::class);
	}
}
