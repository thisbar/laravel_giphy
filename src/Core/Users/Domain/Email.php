<?php

declare(strict_types=1);

namespace LaravelGiphy\Core\Users\Domain;

use InvalidArgumentException;
use LaravelGiphy\Shared\Domain\ValueObject\StringValueObject;

final class Email extends StringValueObject
{
	public function __construct(string $value)
	{
		$this->ensureIsValidEmail($value);
		parent::__construct($value);
	}

	private function ensureIsValidEmail(string $value): void
	{
		if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
			throw new InvalidArgumentException(sprintf('Invalid email address: <%s>', $value));
		}
	}

	public static function from(string $value): static
	{
		return new self($value);
	}
}
