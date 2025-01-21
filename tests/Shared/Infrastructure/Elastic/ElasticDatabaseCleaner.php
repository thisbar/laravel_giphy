<?php

declare(strict_types=1);

namespace LaravelGhipy\Tests\Shared\Infrastructure\Elastic;

use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\Response\Elasticsearch;
use Http\Promise\Promise;
use Nyholm\Psr7\Request;

use function Lambdish\Phunctional\each;

final class ElasticDatabaseCleaner
{
	private Client $client;

	public function __invoke(Client $client): void
	{
		$this->client = $client;
		$this->deleteIndices();
		$this->deleteDataStreams();
	}

	private function deleteDataStreams(): void
	{
		$response    = $this->sendRequest('GET', '/_data_stream');
		$dataStreams = json_decode((string) $response->getBody(), true)['data_streams'] ?? [];

		each(
			function (array $dataStream): void {
				$dataStreamName = $dataStream['name'];
				$this->sendRequest('DELETE', "/_data_stream/$dataStreamName");
			},
			$dataStreams
		);
	}

	private function deleteIndices(): void
	{
		$response = $this->sendRequest('GET', '/_cat/indices?format=json');
		$indices  = json_decode((string) $response->getBody(), true);

		each(
			function (array $index): void {
				$indexName = $index['index'];

				if (!str_starts_with($indexName, '.')) {
					$this->sendRequest('DELETE', "/$indexName");
					$this->sendRequest('PUT', "/$indexName");
				}
			},
			$indices
		);
	}

	public function sendRequest(string $method, string $uri): Elasticsearch | Promise
	{
		$request = new Request($method, $uri);
		return $this->client->sendRequest($request);
	}
}
