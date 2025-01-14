<?php

declare(strict_types=1);

namespace LaravelGhipy\Tests\Core\Gifs\Application;

use LaravelGhipy\Core\Gifs\Application\GifFinder;
use LaravelGhipy\Core\Gifs\Domain\Gif;
use LaravelGhipy\Core\Gifs\Domain\GifsRepository;
use LaravelGhipy\Core\Gifs\Domain\GifTitle;
use LaravelGhipy\Core\Gifs\Domain\GifUrl;
use LaravelGhipy\Core\Gifs\Domain\Search\SearchResult;
use LaravelGhipy\Shared\Domain\Gifs\GifId;
use LaravelGhipy\Shared\Domain\ValueObject\HttpStatusCode;
use LaravelGhipy\Shared\Domain\ValueObject\Search\MetadataMessage;
use LaravelGhipy\Shared\Domain\ValueObject\Search\SearchMetadata;
use LaravelGhipy\Tests\Shared\Infrastructure\PhpUnit\UnitTestCase;
use Mockery;
use Mockery\MockInterface;

final class GifFinderTest extends UnitTestCase
{
	private GifsRepository | MockInterface | null $repository;

	protected function setUp(): void
	{
		parent::setUp();
	}

	public function tearDown(): void
	{
		Mockery::close();
	}

	public function test_it_should_return_the_found_gif_with_valid_matching_id(): void
	{
		$gif = new Gif(new GifId('1'), new GifTitle('test gif'), new GifUrl('http://example.com/gif1.gif'));

		$metadata = new SearchMetadata(new MetadataMessage('OK'), new HttpStatusCode(200));

		$expectedResult = new SearchResult($gif, $metadata);

		$this->repository()->shouldReceive('searchById')
			->once()
			->with(Mockery::type(GifId::class))
			->andReturn($expectedResult);

		$gifFinder = new GifFinder($this->repository);

		$result = $gifFinder->find(new GifId('1'));

		$this->assertEquals($expectedResult, $result);
	}

	public function test_it_should_return_a_gif_not_found_message_when_not_found(): void
	{
		$metadata = new SearchMetadata(new MetadataMessage('Not Found'), new HttpStatusCode(404));

		$expectedResult = new SearchResult(null, $metadata);

		$this->repository()->shouldReceive('searchById')
			->once()
			->with(Mockery::type(GifId::class))
			->andReturn($expectedResult);

		$gifFinder = new GifFinder($this->repository);
		$result    = $gifFinder->find(new GifId('not-existing-id'));

		$this->assertEquals($expectedResult, $result);
	}

	protected function repository(): GifsRepository | MockInterface
	{
		return $this->repository ??= $this->mock(GifsRepository::class);
	}
}
