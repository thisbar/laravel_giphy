<?php

declare(strict_types=1);

namespace LaravelGhipy\Shared\Domain\ValueObject\Search;

use LaravelGhipy\Shared\Domain\ValueObject\HttpStatusCode;

final class SearchMetadata
{
	public function __construct(
		private MetadataMessage $message,
		private HttpStatusCode $statusCode
	) {}

	public function message(): string
	{
		return $this->message->value();
	}

	public function statusCode(): int
	{
		return $this->statusCode->value();
	}

	public function toArray(): array
	{
		return [
			'message'     => $this->message(),
			'status_code' => $this->statusCode(),
		];
	}
}
