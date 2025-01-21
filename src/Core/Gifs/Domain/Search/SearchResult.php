<?php

declare(strict_types=1);

namespace LaravelGiphy\Core\Gifs\Domain\Search;

use LaravelGiphy\Core\Gifs\Domain\Gif;
use LaravelGiphy\Core\Gifs\Domain\GifCollection;
use LaravelGiphy\Shared\Domain\ValueObject\Search\SearchMetadata;

class SearchResult
{
	public function __construct(
		protected readonly Gif | GifCollection | null $data,
		protected readonly SearchMetadata $metadata
	) {}

	public function data(): ?array
	{
		return $this->data?->toArray();
	}

	public function metadata(): array
	{
		return (array) $this->metadata;
	}

	public function statusCode(): int
	{
		return $this->metadata->statusCode();
	}

	public function metadataMessage(): string
	{
		return $this->metadata->message();
	}

	public function toArray(): array
	{
		return [
			'data'     => $this->data?->toArray(),
			'metadata' => $this->metadata->toArray(),
		];
	}
}
