<?php

declare(strict_types=1);

namespace LaravelGhipy\Core\Gifs\Domain;

use LaravelGhipy\Core\Gifs\Domain\Search\PaginatedSearchResult;
use LaravelGhipy\Core\Gifs\Domain\Search\SearchResult;
use LaravelGhipy\Shared\Domain\Gifs\GifId;
use LaravelGhipy\Shared\Domain\ValueObject\Search\Limit;
use LaravelGhipy\Shared\Domain\ValueObject\Search\Offset;
use LaravelGhipy\Shared\Domain\ValueObject\Search\SearchQuery;

interface GifsRepository
{
	public function search(SearchQuery $query, Limit $limit, Offset $offset): PaginatedSearchResult;
	public function searchById(GifId $id): SearchResult;
}
