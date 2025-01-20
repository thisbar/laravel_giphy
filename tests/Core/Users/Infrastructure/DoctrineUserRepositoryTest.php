<?php

declare(strict_types=1);

namespace LaravelGhipy\Tests\Core\Users\Infrastructure;

use Doctrine\ORM\EntityManagerInterface;
use LaravelGhipy\Core\Users\Domain\Email;
use LaravelGhipy\Core\Users\Domain\Password;
use LaravelGhipy\Core\Users\Domain\User;
use LaravelGhipy\Core\Users\Infrastructure\DoctrineUserRepository;
use LaravelGhipy\Shared\Domain\Users\UserId;
use LaravelGhipy\Tests\Core\Shared\Infrastructure\Phpunit\CoreInfrastructureTestCase;

final class DoctrineUserRepositoryTest extends CoreInfrastructureTestCase
{
	private DoctrineUserRepository $repository;
	private EntityManagerInterface $entityManager;

	protected function setUp(): void
	{
		parent::setUp();

		$this->entityManager = $this->service(EntityManagerInterface::class);
		$this->repository    = $this->service(DoctrineUserRepository::class);
	}

	public function test_it_returns_user_when_found(): void
	{
		// Given
		$userId       = UserId::random();
		$email        = new Email('test-user@test.com');
		$password     = Password::from('password123');
		$expectedUser = new User($userId, $email, $password);
		$this->repository->save($expectedUser);

		// When
		$foundUser = $this->repository->search($userId);

		// Then
		$this->assertEquals($expectedUser, $foundUser);
	}

	public function test_it_returns_null_when_user_not_found(): void
	{
		// Given
		$userId = UserId::random();

		// When
		$nullUser = $this->repository->search($userId);

		// Then
		$this->assertNull($nullUser);
	}

	public function test_it_finds_user_by_email(): void
	{
		// Given
		$email    = new Email('test-user@test.com');
		$password = Password::from('password123');
		$user     = User::create($email, $password);

		$this->entityManager->persist($user);
		$this->entityManager->flush();

		// When
		$foundUser = $this->repository->searchByEmail($email);

		// Then
		$this->assertNotNull($foundUser);
		$this->assertEquals($email->value(), $foundUser->email());
	}

	public function test_it_returns_null_when_email_not_found(): void
	{
		// Given
		$email = new Email('non.existent@example.com');

		// When
		$nullUser = $this->repository->searchByEmail($email);

		// Then
		$this->assertNull($nullUser);
	}
}
