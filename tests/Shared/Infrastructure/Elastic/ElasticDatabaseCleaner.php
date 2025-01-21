<?php

declare(strict_types=1);

namespace LaravelGiphy\Tests\Shared\Infrastructure\Elastic;

use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\Response\Elasticsearch;
use Http\Promise\Promise;
use Nyholm\Psr7\Request;
use stdClass;

use function Lambdish\Phunctional\each;

final class ElasticDatabaseCleaner
{
	private Client $client;

	public function __invoke(Client $client): void
	{
		$this->client = $client;
		$this->emptyDataStreams();
		$this->emptyIndices();
	}

	private function emptyDataStreams(): void
	{
		$response    = $this->sendRequest('GET', '/_data_stream');
		$dataStreams = json_decode((string) $response->getBody(), true)['data_streams'] ?? [];

		each(
			function (array $dataStream): void {
				$dataStreamName = $dataStream['name'];

				// Vaciar documentos del data stream, ignorando conflictos
				$this->sendRequest('POST', "/$dataStreamName/_delete_by_query", [
					'body'    => json_encode([
						'query'     => ['match_all' => new stdClass()],
						'conflicts' => 'proceed',
					]),
					'headers' => ['Content-Type' => 'application/json'],
				]);
			},
			$dataStreams
		);
	}

	private function emptyIndices(): void
	{
		$response = $this->sendRequest('GET', '/_cat/indices?format=json');
		$indices  = json_decode((string) $response->getBody(), true);

		each(
			function (array $index): void {
				$indexName = $index['index'];

				if (!str_starts_with($indexName, '.')) {
					$this->sendRequest('POST', "/$indexName/_delete_by_query", [
						'body'    => json_encode([
							'query'     => ['match_all' => new stdClass()],
							'conflicts' => 'proceed',
						]),
						'headers' => ['Content-Type' => 'application/json'],
					]);
				}
			},
			$indices
		);
	}


	public function sendRequest(string $method, string $uri, array $options = []): Elasticsearch | Promise
	{
		$headers = $options['headers'] ?? ['Content-Type' => 'application/json'];
		$body    = $options['body'] ?? null;

		$request = new Request($method, $uri, $headers, $body);
		return $this->client->sendRequest($request);
	}
}
