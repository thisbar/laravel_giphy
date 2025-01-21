<?php

declare(strict_types=1);

namespace LaravelGiphy\Tests\Core\Gifs\Application;

use InvalidArgumentException;
use LaravelGiphy\Core\Gifs\Application\AllGifsSearcher;
use LaravelGiphy\Core\Gifs\Domain\Gif;
use LaravelGiphy\Core\Gifs\Domain\GifCollection;
use LaravelGiphy\Core\Gifs\Domain\GifsRepository;
use LaravelGiphy\Core\Gifs\Domain\GifTitle;
use LaravelGiphy\Core\Gifs\Domain\GifUrl;
use LaravelGiphy\Core\Gifs\Domain\Search\PaginatedSearchResult;
use LaravelGiphy\Shared\Domain\Gifs\GifId;
use LaravelGiphy\Shared\Domain\ValueObject\Count;
use LaravelGiphy\Shared\Domain\ValueObject\HttpStatusCode;
use LaravelGiphy\Shared\Domain\ValueObject\Search\Limit;
use LaravelGiphy\Shared\Domain\ValueObject\Search\MetadataMessage;
use LaravelGiphy\Shared\Domain\ValueObject\Search\Offset;
use LaravelGiphy\Shared\Domain\ValueObject\Search\Pagination;
use LaravelGiphy\Shared\Domain\ValueObject\Search\SearchMetadata;
use LaravelGiphy\Shared\Domain\ValueObject\Search\SearchQuery;
use LaravelGiphy\Tests\Shared\Infrastructure\PhpUnit\UnitTestCase;
use Mockery;
use Mockery\MockInterface;

final class AllGifsSearcherTest extends UnitTestCase
{
	private GifsRepository | MockInterface | null $repository;

	protected function setUp(): void
	{
		parent::setUp();

		$this->repository();
	}

	public function tearDown(): void
	{
		Mockery::close();
	}

	public function test_it_should_return_gifs_with_valid_parameters(): void
	{
		$gifs = new GifCollection([
			new Gif(new GifId('1'), new GifTitle('Example GIF 1'), new GifUrl('http://example.com/gif1.gif')),
			new Gif(new GifId('2'), new GifTitle('Example GIF 2'), new GifUrl('http://example.com/gif2.gif')),
		]);

		$metadata       = new SearchMetadata(new MetadataMessage('OK'), new HttpStatusCode(200));
		$pagination     = new Pagination(new Count(2), new Count(500), new Offset(0));
		$expectedResult = new PaginatedSearchResult($gifs, $metadata, $pagination);

		$this->repository()->shouldReceive('search')
			->once()
			->with(Mockery::type(SearchQuery::class), Mockery::type(Limit::class), Mockery::type(Offset::class))
			->andReturn($expectedResult);

		$gifsSearcher = new AllGifsSearcher($this->repository);
		$result       = $gifsSearcher->search(new SearchQuery('funny'), new Limit(10), new Offset(0));

		$this->assertEquals($expectedResult, $result);
	}

	public function test_it_should_return_an_empty_array_if_the_query_does_not_match_any_gif(): void
	{
		$gifs           = new GifCollection([]);
		$metadata       = new SearchMetadata(new MetadataMessage('OK'), new HttpStatusCode(200));
		$pagination     = new Pagination(new Count(0), new Count(0), new Offset(0));
		$expectedResult = new PaginatedSearchResult($gifs, $metadata, $pagination);

		$this->repository()->shouldReceive('search')
			->once()
			->with(Mockery::type(SearchQuery::class), Mockery::type(Limit::class), Mockery::type(Offset::class))
			->andReturn($expectedResult);

		$gifsSearcher = new AllGifsSearcher($this->repository);
		$result       = $gifsSearcher->search(new SearchQuery('not_found'), new Limit(10), new Offset(0));

		$this->assertEquals($expectedResult, $result);
	}

	public function test_it_should_return_just_one_gif_with_limit_one(): void
	{
		$gifs = new GifCollection([
			new Gif(new GifId('1'), new GifTitle('Example GIF 1'), new GifUrl('http://example.com/gif1.gif')),
		]);
		$metadata       = new SearchMetadata(new MetadataMessage('OK'), new HttpStatusCode(200));
		$pagination     = new Pagination(new Count(1), new Count(500), new Offset(0));
		$expectedResult = new PaginatedSearchResult($gifs, $metadata, $pagination);

		$this->repository()->shouldReceive('search')
			->once()
			->with(Mockery::type(SearchQuery::class), Mockery::type(Limit::class), Mockery::type(Offset::class))
			->andReturn($expectedResult);

		$gifsSearcher = new AllGifsSearcher($this->repository);
		$result       = $gifsSearcher->search(new SearchQuery('funny'), new Limit(1), new Offset(0));

		$this->assertEquals($expectedResult, $result);
	}

	public function test_it_should_throw_invalid_argument_exception_with_query_under_minimum_length(): void
	{
		$this->expectException(InvalidArgumentException::class);
		$this->repository()->shouldReceive('search')
			->never()
			->with(Mockery::type(SearchQuery::class), Mockery::type(Limit::class), Mockery::type(Offset::class));

		$gifsSearcher = new AllGifsSearcher($this->repository);

		$gifsSearcher->search(new SearchQuery(''), new Limit(5), new Offset(0));
	}

	public function test_it_should_throw_invalid_argument_exception_with_query_above_maximum_length(): void
	{
		$this->expectException(InvalidArgumentException::class);
		$this->repository()->shouldReceive('search')
			->never()
			->with(Mockery::type(SearchQuery::class), Mockery::type(Limit::class), Mockery::type(Offset::class));

		$gifsSearcher = new AllGifsSearcher($this->repository);

		$gifsSearcher->search(new SearchQuery(str_repeat('a', 256)), new Limit(1), new Offset(0));
	}

	public function test_it_should_throw_invalid_argument_exception_with_limit_under_minimum(): void
	{
		$this->expectException(InvalidArgumentException::class);
		$this->repository()->shouldReceive('search')
			->never()
			->with(Mockery::type(SearchQuery::class), Mockery::type(Limit::class), Mockery::type(Offset::class));

		$gifsSearcher = new AllGifsSearcher($this->repository);

		$gifsSearcher->search(new SearchQuery('funny'), new Limit(0), new Offset(0));
	}

	public function test_it_should_throw_invalid_argument_exception_with_limit_above_maximum(): void
	{
		$this->expectException(InvalidArgumentException::class);
		$this->repository()->shouldReceive('search')
			->never()
			->with(Mockery::type(SearchQuery::class), Mockery::type(Limit::class), Mockery::type(Offset::class));

		$gifsSearcher = new AllGifsSearcher($this->repository);
		$gifsSearcher->search(new SearchQuery('funny'), new Limit(99999), new Offset(0));
	}

	public function test_it_should_throw_invalid_argument_exception_with_offset_under_minimum(): void
	{
		$this->expectException(InvalidArgumentException::class);
		$this->repository()->shouldReceive('search')
			->never()
			->with(Mockery::type(SearchQuery::class), Mockery::type(Limit::class), Mockery::type(Offset::class));

		$gifsSearcher = new AllGifsSearcher($this->repository);
		$gifsSearcher->search(new SearchQuery('funny'), new Limit(5), new Offset(-5));
	}

	public function test_it_should_throw_invalid_argument_exception_with_offset_above_maximum(): void
	{
		$this->expectException(InvalidArgumentException::class);
		$this->repository()->shouldReceive('search')
			->never()
			->with(Mockery::type(SearchQuery::class), Mockery::type(Limit::class), Mockery::type(Offset::class));

		$gifsSearcher = new AllGifsSearcher($this->repository);
		$gifsSearcher->search(new SearchQuery('funny'), new Limit(5), new Offset(99999));
	}

	protected function repository(): GifsRepository | MockInterface
	{
		return $this->repository ??= $this->mock(GifsRepository::class);
	}
}
