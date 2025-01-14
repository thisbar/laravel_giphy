<?php

declare(strict_types=1);

namespace LaravelGhipy\Core\Gifs\Application;

use LaravelGhipy\Core\Gifs\Domain\GifsRepository;
use LaravelGhipy\Core\Gifs\Domain\Search\PaginatedSearchResult;
use LaravelGhipy\Shared\Domain\ValueObject\Search\Limit;
use LaravelGhipy\Shared\Domain\ValueObject\Search\Offset;
use LaravelGhipy\Shared\Domain\ValueObject\Search\SearchQuery;

final class AllGifsSearcher
{
	public function __construct(private readonly GifsRepository $repository) {}

	public function search(SearchQuery $query, Limit $limit, Offset $offset): PaginatedSearchResult
	{
		return $this->repository->search($query, $limit, $offset);
	}
}
