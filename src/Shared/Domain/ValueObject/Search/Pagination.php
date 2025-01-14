<?php

declare(strict_types=1);

namespace LaravelGhipy\Shared\Domain\ValueObject\Search;

use LaravelGhipy\Shared\Domain\ValueObject\Count;

final class Pagination
{
	public function __construct(
		private Count $count,
		private Count $totalCount,
		private Offset $offset
	) {}

	public function count(): int
	{
		return $this->count->value();
	}

	public function totalCount(): int
	{
		return $this->totalCount->value();
	}

	public function offset(): int
	{
		return $this->offset->value();
	}

	public function toArray(): array
	{
		return [
			'count'       => $this->count(),
			'total_count' => $this->totalCount(),
			'offset'      => $this->offset(),
		];
	}
}
