<?php

declare(strict_types=1);

namespace LaravelGhipy\Core\Gifs\Domain;

use LaravelGhipy\Shared\Domain\DomainError;
use LaravelGhipy\Shared\Domain\Gifs\GifId;

final class GifNotFound extends DomainError
{
	public const HTTP_NOT_FOUND = 409;

	public function __construct(private readonly GifId $id)
	{
		parent::__construct();
	}

	public function errorCode(): int
	{
		return self::HTTP_NOT_FOUND;
	}

	protected function errorMessage(): string
	{
		return sprintf('The gif <%s> was not found', $this->id->value());
	}
}
