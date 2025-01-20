<?php

declare(strict_types=1);

namespace LaravelGhipy\Tests\Core\Users\Application;

use LaravelGhipy\Core\Users\Application\UserIdSearcher;
use LaravelGhipy\Core\Users\Domain\Email;
use LaravelGhipy\Core\Users\Domain\Password;
use LaravelGhipy\Core\Users\Domain\User;
use LaravelGhipy\Core\Users\Domain\UserRepository;
use LaravelGhipy\Shared\Domain\Users\UserId;
use LaravelGhipy\Tests\Shared\Infrastructure\PhpUnit\UnitTestCase;
use Mockery;
use Mockery\MockInterface;

final class UserIdSearcherTest extends UnitTestCase
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
		$userId       = UserId::random();
		$email        = new Email('test-user@test.com');
		$password     = Password::from('password123');
		$existingUser = new User($userId, $email, $password);

		$this->repository()
			->shouldReceive('search')
			->once()
			->with($userId)
			->andReturn($existingUser);

		// When
		$userIdSearcher = new UserIdSearcher($this->repository);
		$userResult     = $userIdSearcher->search($userId);

		// Then
		$this->assertSame($userResult, $existingUser);
	}

	public function test_it_returns_null_when_user_not_found(): void
	{
		// Given
		$userId = UserId::random();

		$this->repository()
			->shouldReceive('search')
			->once()
			->with($userId)
			->andReturn(null);

		// When
		$userIdSearcher = new UserIdSearcher($this->repository);
		$userResult     = $userIdSearcher->search($userId);

		// Then
		$this->assertNull($userResult);
	}

	private function repository(): MockInterface | UserRepository
	{
		return $this->repository ??= $this->mock(UserRepository::class);
	}
}
