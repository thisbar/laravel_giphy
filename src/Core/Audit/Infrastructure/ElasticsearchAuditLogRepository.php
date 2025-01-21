<?php

declare(strict_types=1);

namespace LaravelGhipy\Core\Audit\Infrastructure;

use Elastic\Elasticsearch\Client;
use LaravelGhipy\Core\Audit\Domain\AuditLog;
use LaravelGhipy\Core\Audit\Domain\AuditLogRepository;

final class ElasticsearchAuditLogRepository implements AuditLogRepository
{
	public function __construct(private Client $client) {}

	public function save(AuditLog $log): void
	{
		$this->client->index([
			'index' => 'audit_logs',
			'body'  => $log->toArray(),
		]);
	}
}
