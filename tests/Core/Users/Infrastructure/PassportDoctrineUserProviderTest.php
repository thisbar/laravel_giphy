<?php

declare(strict_types=1);

namespace LaravelGiphy\Tests\Core\Users\Infrastructure;

use Doctrine\ORM\EntityManagerInterface;
use LaravelGiphy\Core\Users\Domain\Email;
use LaravelGiphy\Core\Users\Domain\Password;
use LaravelGiphy\Core\Users\Domain\User;
use LaravelGiphy\Core\Users\Infrastructure\PassportDoctrineUserProvider;
use LaravelGiphy\Core\Users\Infrastructure\PassportUserAdapter;
use LaravelGiphy\Shared\Domain\Users\UserId;
use LaravelGiphy\Tests\Core\Shared\Infrastructure\Phpunit\CoreInfrastructureTestCase;

final class PassportDoctrineUserProviderTest extends CoreInfrastructureTestCase
{
	private PassportDoctrineUserProvider $provider;
	private EntityManagerInterface $entityManager;

	protected function setUp(): void
	{
		parent::setUp();

		$this->entityManager = $this->service(EntityManagerInterface::class);
		$this->provider      = $this->service(PassportDoctrineUserProvider::class);
	}

	public function test_it_returns_user_adapter_when_user_is_found_by_id(): void
	{
		// Given
		$userId       = UserId::random();
		$email        = new Email('test-user@test.com');
		$password     = Password::from('password123');
		$expectedUser = new User($userId, $email, $password);
		$this->entityManager->persist($expectedUser);
		$this->entityManager->flush();

		// When
		$actualUser = $this->provider->retrieveById($userId->value());

		// Then
		$this->assertInstanceOf(PassportUserAdapter::class, $actualUser);
		$this->assertEquals($expectedUser, $actualUser->user);
	}

	public function test_it_returns_null_when_user_is_not_found_by_id(): void
	{
		// Given
		$nonExistentId = UserId::random()->value();

		// When
		$actualUser = $this->provider->retrieveById($nonExistentId);

		// Then
		$this->assertNull($actualUser);
	}

	public function test_it_returns_user_adapter_when_user_is_found_by_credentials(): void
	{
		// Given
		$email        = new Email('test-user@test.com');
		$password     = Password::from('password123');
		$expectedUser = new User(UserId::random(), $email, $password);
		$this->entityManager->persist($expectedUser);
		$this->entityManager->flush();

		// When
		$actualUser = $this->provider->retrieveByCredentials(['email' => $email]);

		// Then
		$this->assertInstanceOf(PassportUserAdapter::class, $actualUser);
		$this->assertEquals($expectedUser, $actualUser->user);
	}

	public function test_it_returns_null_when_credentials_do_not_match_any_user(): void
	{
		// Given
		$email = new Email('non-existent@test.com');

		// When
		$actualUser = $this->provider->retrieveByCredentials(['email' => $email]);

		// Then
		$this->assertNull($actualUser);
	}

	public function test_it_validates_user_credentials(): void
	{
		// Given
		$email    = new Email('test-user@test.com');
		$password = Password::from('password123');
		$user     = new User(UserId::random(), $email, $password);
		$this->entityManager->persist($user);
		$this->entityManager->flush();

		$actualUser = $this->provider->retrieveByCredentials(['email' => $email]);

		// When
		$isValid = $this->provider->validateCredentials($actualUser, ['password' => 'password123']);

		// Then
		$this->assertTrue($isValid);
	}

	public function test_it_fails_to_validate_invalid_user_credentials(): void
	{
		// Given
		$email    = new Email('test-user@test.com');
		$password = Password::from('password123');
		$user     = new User(UserId::random(), $email, $password);
		$this->entityManager->persist($user);
		$this->entityManager->flush();

		$actualUser = $this->provider->retrieveByCredentials(['email' => $email]);

		// When
		$isValid = $this->provider->validateCredentials($actualUser, ['password' => 'wrong-password']);

		// Then
		$this->assertFalse($isValid);
	}
}
