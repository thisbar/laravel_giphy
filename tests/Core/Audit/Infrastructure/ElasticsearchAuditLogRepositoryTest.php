<?php

declare(strict_types=1);

namespace LaravelGiphy\Tests\Core\Audit\Infrastructure;

use DateTimeImmutable;
use Elastic\Elasticsearch\Client;
use LaravelGiphy\Core\Audit\Domain\AuditLog;
use LaravelGiphy\Core\Audit\Infrastructure\ElasticsearchAuditLogRepository;
use LaravelGiphy\Tests\Core\Shared\Infrastructure\Phpunit\CoreInfrastructureTestCase;

final class ElasticsearchAuditLogRepositoryTest extends CoreInfrastructureTestCase
{
	private ElasticsearchAuditLogRepository $repository;
	private Client $client;

	protected function setUp(): void
	{
		parent::setUp();

		$this->client     = $this->service(Client::class);
		$this->repository = new ElasticsearchAuditLogRepository($this->client);
	}

	public function test_it_saves_audit_log(): void
	{
		// Given
		$log = new AuditLog(
			userId: 'test-user-id',
			service: 'api/auth/login',
			requestBody: ['email' => 'test@example.com'],
			httpStatusCode: 200,
			responseBody: ['message' => 'success'],
			ipAddress: '127.0.0.1',
			timestamp: new DateTimeImmutable()
		);

		// When
		$this->repository->save($log);
		$this->refreshElasticIndex('audit_logs');

		// Then
		$response = $this->client->search([
			'index' => 'audit_logs',
			'body'  => [
				'query' => [
					'term' => ['user_id' => 'test-user-id'],
				],
			],
		]);

		$this->assertEquals(1, $response['hits']['total']['value']);
		$this->assertEquals('test-user-id', $response['hits']['hits'][0]['_source']['user_id']);
	}
}
