<?php

declare(strict_types=1);

namespace LaravelGiphy\Core\Gifs\Application;

use LaravelGiphy\Core\Gifs\Domain\GifsRepository;
use LaravelGiphy\Core\Gifs\Domain\Search\SearchResult;
use LaravelGiphy\Shared\Domain\Gifs\GifId;

final class GifFinder
{
	public function __construct(private readonly GifsRepository $repository) {}

	public function find(GifId $id): SearchResult
	{
		return $this->repository->searchById($id);
	}
}
