<?php

declare(strict_types=1);

namespace LaravelGiphy\Core\Audit\Infrastructure;

use Elastic\Elasticsearch\Client;
use LaravelGiphy\Core\Audit\Domain\AuditLog;
use LaravelGiphy\Core\Audit\Domain\AuditLogRepository;

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
