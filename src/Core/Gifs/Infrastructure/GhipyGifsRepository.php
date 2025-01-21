<?php

declare(strict_types=1);

namespace LaravelGiphy\Core\Gifs\Infrastructure;

use LaravelGiphy\Core\Gifs\Domain\Gif;
use LaravelGiphy\Core\Gifs\Domain\GifCollection;
use LaravelGiphy\Core\Gifs\Domain\GifsRepository;
use LaravelGiphy\Core\Gifs\Domain\GifTitle;
use LaravelGiphy\Core\Gifs\Domain\GifUrl;
use LaravelGiphy\Core\Gifs\Domain\Search\PaginatedSearchResult;
use LaravelGiphy\Core\Gifs\Domain\Search\SearchResult;
use LaravelGiphy\Shared\Domain\Gifs\GifId;
use LaravelGiphy\Shared\Domain\HttpClient;
use LaravelGiphy\Shared\Domain\ValueObject\Count;
use LaravelGiphy\Shared\Domain\ValueObject\HttpStatusCode;
use LaravelGiphy\Shared\Domain\ValueObject\Search\Limit;
use LaravelGiphy\Shared\Domain\ValueObject\Search\MetadataMessage;
use LaravelGiphy\Shared\Domain\ValueObject\Search\Offset;
use LaravelGiphy\Shared\Domain\ValueObject\Search\Pagination;
use LaravelGiphy\Shared\Domain\ValueObject\Search\SearchMetadata;
use LaravelGiphy\Shared\Domain\ValueObject\Search\SearchQuery;

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
