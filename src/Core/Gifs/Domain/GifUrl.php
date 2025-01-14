<?php

declare(strict_types=1);

namespace LaravelGhipy\Core\Gifs\Domain;

use InvalidArgumentException;
use LaravelGhipy\Shared\Domain\ValueObject\StringValueObject;

final class GifUrl extends StringValueObject
{
	public function __construct(string $value)
	{
		$this->ensureIsValidUrl($value);

		parent::__construct($value);
	}

	private function ensureIsValidUrl(string $url): void
	{
		if (filter_var($url, FILTER_VALIDATE_URL) === false) {
			throw new InvalidArgumentException(sprintf('The url <%s> is not well formatted', $url));
		}
	}
}
