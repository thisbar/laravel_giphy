<?php

declare(strict_types=1);

namespace LaravelGhipy\Core\Auth\Infrastructure;

use Carbon\Carbon;
use Laravel\Passport\PersonalAccessTokenFactory;
use LaravelGhipy\Core\Users\Domain\User;

final class TokenGenerator
{
	public function __construct(
		private PersonalAccessTokenFactory $tokenFactory
	) {}

	public function generate(User $user, array $scopes = ['*']): array
	{
		$tokenResult = $this->tokenFactory->make($user->id(), 'Personal Access Token', $scopes);
		$expiresAt   = Carbon::parse($tokenResult->token->expires_at)->setTimezone(config('app.timezone'));

		return [
			'token'      => $tokenResult->accessToken,
			'expires_at' => $expiresAt->format(config('passport.datetime_format')),
		];
	}
}
