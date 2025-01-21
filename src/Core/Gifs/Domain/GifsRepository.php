<?php

declare(strict_types=1);

namespace LaravelGiphy\Core\Gifs\Domain;

use LaravelGiphy\Core\Gifs\Domain\Search\PaginatedSearchResult;
use LaravelGiphy\Core\Gifs\Domain\Search\SearchResult;
use LaravelGiphy\Shared\Domain\Gifs\GifId;
use LaravelGiphy\Shared\Domain\ValueObject\Search\Limit;
use LaravelGiphy\Shared\Domain\ValueObject\Search\Offset;
use LaravelGiphy\Shared\Domain\ValueObject\Search\SearchQuery;

interface GifsRepository
{
	public function search(SearchQuery $query, Limit $limit, Offset $offset): PaginatedSearchResult;
	public function searchById(GifId $id): SearchResult;
}
