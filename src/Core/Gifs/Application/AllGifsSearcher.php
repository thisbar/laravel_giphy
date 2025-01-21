<?php

declare(strict_types=1);

namespace LaravelGiphy\Core\Gifs\Application;

use LaravelGiphy\Core\Gifs\Domain\GifsRepository;
use LaravelGiphy\Core\Gifs\Domain\Search\PaginatedSearchResult;
use LaravelGiphy\Shared\Domain\ValueObject\Search\Limit;
use LaravelGiphy\Shared\Domain\ValueObject\Search\Offset;
use LaravelGiphy\Shared\Domain\ValueObject\Search\SearchQuery;

final class AllGifsSearcher
{
	public function __construct(private readonly GifsRepository $repository) {}

	public function search(SearchQuery $query, Limit $limit, Offset $offset): PaginatedSearchResult
	{
		return $this->repository->search($query, $limit, $offset);
	}
}
