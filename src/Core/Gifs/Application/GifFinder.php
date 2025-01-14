<?php

declare(strict_types=1);

namespace LaravelGhipy\Core\Gifs\Application;

use LaravelGhipy\Core\Gifs\Domain\GifsRepository;
use LaravelGhipy\Core\Gifs\Domain\Search\SearchResult;
use LaravelGhipy\Shared\Domain\Gifs\GifId;

final class GifFinder
{
	public function __construct(private readonly GifsRepository $repository) {}

	public function find(GifId $id): SearchResult
	{
		return $this->repository->searchById($id);
	}
}
