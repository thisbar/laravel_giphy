<?php

declare(strict_types=1);

namespace LaravelGiphy\Tests\Core\Gifs\Infrastructure;

use LaravelGiphy\Core\Gifs\Domain\Gif;
use LaravelGiphy\Core\Gifs\Domain\GifCollection;
use LaravelGiphy\Core\Gifs\Domain\GifTitle;
use LaravelGiphy\Core\Gifs\Domain\GifUrl;
use LaravelGiphy\Core\Gifs\Domain\Search\PaginatedSearchResult;
use LaravelGiphy\Core\Gifs\Domain\Search\SearchResult;
use LaravelGiphy\Core\Gifs\Infrastructure\GhipyGifsRepository;
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
use LaravelGiphy\Tests\TestCase;

final class GhipyGifsRepositoryTest extends TestCase
{
	private GhipyGifsRepository $repository;

	protected function setUp(): void
	{
		parent::setUp();

		$httpClient       = app(HttpClient::class);
		$this->repository = new GhipyGifsRepository($httpClient);
	}

	public function test_it_should_return_results_for_valid_query(): void
	{
		$query  = new SearchQuery('funny');
		$limit  = new Limit(5);
		$offset = new Offset(0);

		$results = $this->repository->search($query, $limit, $offset);

		$expectedResult = new PaginatedSearchResult(
			new GifCollection([
				new Gif(new GifId('3o7ZeAiCICH5bj1Esg'), new GifTitle('baby lol GIF'), new GifUrl(
					'https://giphy.com/gifs/afv-funny-fail-lol-3o7ZeAiCICH5bj1Esg'
				)),
				new Gif(new GifId('26tP3M3i03hoIYL6M'), new GifTitle('Kid Lol GIF'), new GifUrl(
					'https://giphy.com/gifs/afv-funny-fail-lol-26tP3M3i03hoIYL6M'
				)),
			]),
			new SearchMetadata(new MetadataMessage('OK'), new HttpStatusCode(200)),
			new Pagination(new Count(2), new Count(500), new Offset(0))
		);

		$this->assertEquals($expectedResult, $results);
	}

	public function test_it_should_return_empty_results_for_invalid_query(): void
	{
		$query  = new SearchQuery('no-results');
		$limit  = new Limit(5);
		$offset = new Offset(0);

		$results = $this->repository->search($query, $limit, $offset);

		$expectedResult = new PaginatedSearchResult(
			new GifCollection([]),
			new SearchMetadata(new MetadataMessage('OK'), new HttpStatusCode(200)),
			new Pagination(new Count(0), new Count(0), new Offset(0))
		);

		$this->assertEquals($expectedResult, $results);
	}

	public function test_it_should_return_an_existing_gif_for_valid_id(): void
	{
		$gifId = new GifId('3o7ZeAiCICH5bj1Esg');

		$result = $this->repository->searchById($gifId);

		$expectedResult = new SearchResult(
			new Gif(
				new GifId('3o7ZeAiCICH5bj1Esg'),
				new GifTitle('baby lol GIF'),
				new GifUrl('https://giphy.com/gifs/afv-funny-fail-lol-3o7ZeAiCICH5bj1Esg')
			),
			new SearchMetadata(new MetadataMessage('OK'), new HttpStatusCode(200))
		);

		$this->assertEquals($expectedResult->toArray(), $result->toArray());
	}

	public function test_it_should_return_not_found_for_not_existing_id(): void
	{
		$gifId = new GifId('x4gnt3b9y85l');

		$result = $this->repository->searchById($gifId);

		$expectedResult = new SearchResult(
			null,
			new SearchMetadata(new MetadataMessage('Not Found'), new HttpStatusCode(404))
		);

		$this->assertEquals($expectedResult->toArray(), $result->toArray());
	}

	public function test_it_should_return_error_for_invalid_id(): void
	{
		$gifId = new GifId('invalid-id');

		$result = $this->repository->searchById($gifId);

		$expectedResult = new SearchResult(
			null,
			new SearchMetadata(new MetadataMessage('Validation error'), new HttpStatusCode(400))
		);

		$this->assertEquals($expectedResult->toArray(), $result->toArray());
	}
}
