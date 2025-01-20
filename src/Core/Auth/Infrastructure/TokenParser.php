<?php

declare(strict_types=1);

namespace LaravelGhipy\Core\Auth\Infrastructure;

use Exception;
use InvalidArgumentException;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Token;

final class TokenParser
{
	private Configuration $jwtConfig;

	public function __construct(Configuration $jwtConfig)
	{
		$this->jwtConfig = $jwtConfig;
	}

	public function parseToken(string $tokenString): ?Token
	{
		try {
			if (empty($tokenString)) {
				throw new InvalidArgumentException('Token string cannot be empty.');
			}

			$token = $this->jwtConfig->parser()->parse($tokenString);

			if (!$this->validateToken($token)) {
				return null;
			}

			return $token;
		} catch (Exception $e) {
			return null;
		}
	}

	private function validateToken(Token $token): bool
	{
		return $this->jwtConfig->validator()->validate($token, ...$this->jwtConfig->validationConstraints());
	}
}
