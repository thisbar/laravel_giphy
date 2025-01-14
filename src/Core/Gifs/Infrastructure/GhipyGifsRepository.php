<?php

declare(strict_types=1);

namespace LaravelGhipy\Core\Gifs\Infrastructure;

use LaravelGhipy\Core\Gifs\Domain\Gif;
use LaravelGhipy\Core\Gifs\Domain\GifCollection;
use LaravelGhipy\Core\Gifs\Domain\GifsRepository;
use LaravelGhipy\Core\Gifs\Domain\GifTitle;
use LaravelGhipy\Core\Gifs\Domain\GifUrl;
use LaravelGhipy\Core\Gifs\Domain\Search\PaginatedSearchResult;
use LaravelGhipy\Core\Gifs\Domain\Search\SearchResult;
use LaravelGhipy\Shared\Domain\Gifs\GifId;
use LaravelGhipy\Shared\Domain\HttpClient;
use LaravelGhipy\Shared\Domain\ValueObject\Count;
use LaravelGhipy\Shared\Domain\ValueObject\HttpStatusCode;
use LaravelGhipy\Shared\Domain\ValueObject\Search\Limit;
use LaravelGhipy\Shared\Domain\ValueObject\Search\MetadataMessage;
use LaravelGhipy\Shared\Domain\ValueObject\Search\Offset;
use LaravelGhipy\Shared\Domain\ValueObject\Search\Pagination;
use LaravelGhipy\Shared\Domain\ValueObject\Search\SearchMetadata;
use LaravelGhipy\Shared\Domain\ValueObject\Search\SearchQuery;

final class GhipyGifsRepository implements GifsRepository
{
	private string $baseUrl;
	private string $apiKey;

	public function __construct(private HttpClient $httpClient)
	{
		$this->baseUrl = config('services.giphy.base_url');
		$this->apiKey  = config('services.giphy.api_key');
	}

	public function search(SearchQuery $query, Limit $limit, Offset $offset): PaginatedSearchResult
	{
		$response = $this->httpClient->get("{$this->baseUrl}/search", [
			'api_key' => $this->apiKey,
			'q'       => $query->value(),
			'limit'   => $limit->value(),
			'offset'  => $offset->value(),
		]);

		$gifs       = $this->generateCollectionOfGifFromScalars($response['data']);
		$metadata   = $this->generateSearchMetadata($response['meta']);
		$pagination = new Pagination(
			new Count($response['pagination']['count']),
			new Count($response['pagination']['total_count']),
			new Offset($response['pagination']['offset'])
		);

		return new PaginatedSearchResult($gifs, $metadata, $pagination);
	}

	public function searchById(GifId $id): SearchResult
	{
		$response = $this->httpClient->get("{$this->baseUrl}/{$id->value()}", [
			'api_key' => $this->apiKey,
		]);

		$gif      = $this->generateGifObject($response['data']);
		$metadata = $this->generateSearchMetadata($response['meta']);

		return new SearchResult($gif, $metadata);
	}

	private function generateSearchMetadata(array $metadata): SearchMetadata
	{
		return new SearchMetadata(new MetadataMessage($metadata['msg']), new HttpStatusCode($metadata['status']));
	}

	private function generateGifObject(array $gif): ?Gif
	{
		if (empty($gif)) {
			return null;
		}

		return new Gif(new GifId($gif['id']), new GifTitle($gif['title']), new GifUrl($gif['url']));
	}

	private function generateCollectionOfGifFromScalars(array $gifs): GifCollection
	{
		$collection = [];
		foreach ($gifs as $gif) {
			$collection[] = $this->generateGifObject($gif);
		}

		return new GifCollection($collection);
	}
}
