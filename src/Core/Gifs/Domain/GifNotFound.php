<?php

declare(strict_types=1);

namespace LaravelGhipy\Core\Gifs\Domain;

use LaravelGhipy\Shared\Domain\DomainError;
use LaravelGhipy\Shared\Domain\Gifs\GifId;

final class GifNotFound extends DomainError
{
	public function __construct(private readonly GifId $id)
	{
		parent::__construct();
	}

	public function errorCode(): string
	{
		return 'gif_not_found';
	}

	protected function errorMessage(): string
	{
		return sprintf('The gif <%s> was not found', $this->id->value());
	}
}
