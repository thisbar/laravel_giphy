<?php

declare(strict_types=1);

namespace LaravelGhipy\Shared\Domain\ValueObject;

use InvalidArgumentException;
use Ramsey\Uuid\Uuid as RamseyUuid;

abstract class Uuid extends StringValueObject
{
	final public function __construct(string $value)
	{
		$this->ensureIsValidUuid($value);

		parent::__construct($value);
	}

	final public static function random(): static
	{
		return new static(RamseyUuid::uuid4()->toString());
	}

	public function __toString(): string
	{
		return $this->value();
	}

	private function ensureIsValidUuid(string $id): void
	{
		if (!RamseyUuid::isValid($id)) {
			throw new InvalidArgumentException(sprintf('<%s> does not allow the value <%s>.', self::class, $id));
		}
	}
}
