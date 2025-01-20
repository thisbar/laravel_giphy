<?php

declare(strict_types=1);

namespace LaravelGhipy\Core\Auth\Infrastructure;

use Exception;
use Illuminate\Support\Carbon;
use InvalidArgumentException;
use Laravel\Passport\Token as PassportToken;
use Laravel\Passport\TokenRepository;
use LaravelGhipy\Core\Users\Application\UserIdSearcher;
use LaravelGhipy\Core\Users\Domain\UserRepository;
use LaravelGhipy\Shared\Domain\Users\UserId;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Token as JwtToken;
use Lcobucci\JWT\Token\Plain;

final class TokenValidator
{
	private Configuration $jwtConfig;
	private TokenRepository $tokenRepository;
	private UserIdSearcher $userSearcher;

	public function __construct(
		Configuration $jwtConfig,
		TokenRepository $tokenRepository,
		UserRepository $userRepository
	) {
		$this->jwtConfig       = $jwtConfig;
		$this->tokenRepository = $tokenRepository;
		$this->userSearcher    = new UserIdSearcher($userRepository);
	}

	public function validate(string $jwtToken): ?array
	{
		try {
			$token = $this->parseJwt($jwtToken);

			$tokenId       = $this->extractOauthTokenIdFromJwt($token);
			$passportToken = $this->tokenRepository->find($tokenId);

			if (!$this->isOAuthTokenValid($passportToken)) {
				return null;
			}

			return $this->getUserData($passportToken);
		} catch (Exception $e) {
			return null;
		}
	}

	private function isOAuthTokenValid(PassportToken $passportToken): bool
	{
		return !$passportToken->revoked &&
			$passportToken->expires_at >= Carbon::now();
	}

	private function getUserData(PassportToken $passportToken): ?array
	{
		$user = $this->userSearcher->search(UserId::from($passportToken->user_id));

		return $user ? [
			'id'    => $user->id(),
			'email' => $user->email(),
		] : null;
	}

	private function parseJwt(string $jwtToken): JwtToken
	{
		if (empty($jwtToken)) {
			throw new InvalidArgumentException('Token string cannot be empty.');
		}

		return $this->jwtConfig->parser()->parse($jwtToken);
	}

	private function extractOauthTokenIdFromJwt(JwtToken $token): mixed
	{
		if (!$token instanceof Plain) {
			throw new InvalidArgumentException('Expected a Plain token.');
		}

		return $token->claims()->get('jti');
	}
}
