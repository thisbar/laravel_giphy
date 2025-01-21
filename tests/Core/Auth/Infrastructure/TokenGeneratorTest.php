<?php

declare(strict_types=1);

namespace LaravelGiphy\Tests\Core\Auth\Infrastructure;

use Carbon\Carbon;
use Laravel\Passport\PersonalAccessTokenFactory;
use Laravel\Passport\PersonalAccessTokenResult;
use Laravel\Passport\Token;
use LaravelGiphy\Core\Auth\Infrastructure\TokenGenerator;
use LaravelGiphy\Core\Users\Domain\Email;
use LaravelGiphy\Core\Users\Domain\Password;
use LaravelGiphy\Core\Users\Domain\User;
use LaravelGiphy\Tests\TestCase;
use Mockery;
use Mockery\MockInterface;

final class TokenGeneratorTest extends TestCase
{
	private MockInterface | PersonalAccessTokenFactory | null $tokenFactory;

	protected function tearDown(): void
	{
		Mockery::close();
		parent::tearDown();
	}

	public function test_it_generates_a_token(): void
	{
		// Given
		$email    = Email::from('test@example.com');
		$password = Password::from('password123');
		$user     = User::create($email, $password);

		$tokenExpiresAt = $this->tokenExpiresIn();

		$expectedTokenFactoryResult = new PersonalAccessTokenResult(
			'sample_token',
			new Token(['expires_at' => $tokenExpiresAt])
		);

		$expectedTokenGeneratorResult = [
			'token'      => 'sample_token',
			'expires_at' => $tokenExpiresAt,
		];

		$this->tokenFactory()
			->shouldReceive('make')
			->once()
			->with($user->id(), 'Personal Access Token', ['*'])
			->andReturn($expectedTokenFactoryResult);

		$tokenGenerator = new TokenGenerator($this->tokenFactory());

		// When
		$result = $tokenGenerator->generate($user);

		// Then
		$this->assertEquals($expectedTokenGeneratorResult, $result);
	}

	private function tokenExpiresIn(): string
	{
		return Carbon::now()
			->addMinutes(config('tokens_expire_in_minutes'))
			->setTimezone(config('app.timezone'))
			->format(config('passport.datetime_format'));
	}

	private function tokenFactory(): MockInterface | PersonalAccessTokenFactory
	{
		return $this->tokenFactory ??= Mockery::mock(PersonalAccessTokenFactory::class);
	}
}
