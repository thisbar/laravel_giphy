<?php

declare(strict_types=1);

namespace LaravelGiphy\Tests\Core\Audit\Application;

use DateTimeImmutable;
use LaravelGiphy\Core\Audit\Application\AuditEntryLogger;
use LaravelGiphy\Core\Audit\Domain\AuditLog;
use LaravelGiphy\Core\Audit\Domain\AuditLogRepository;
use LaravelGiphy\Tests\Shared\Infrastructure\PhpUnit\UnitTestCase;
use Mockery;
use Mockery\MockInterface;

final class AuditEntryLoggerTest extends UnitTestCase
{
	private AuditLogRepository | MockInterface | null $repository;

	protected function tearDown(): void
	{
		Mockery::close();
		parent::tearDown();
	}

	public function test_it_logs_audit_entry(): void
	{
		// Given
		$userId         = 'test-user-id';
		$service        = 'api/auth/login';
		$requestBody    = ['email' => 'test@example.com'];
		$httpStatusCode = 200;
		$responseBody   = ['message' => 'success'];
		$ipAddress      = '127.0.0.1';

		$log = new AuditLog(
			userId: $userId,
			service: $service,
			requestBody: $requestBody,
			httpStatusCode: $httpStatusCode,
			responseBody: $responseBody,
			ipAddress: $ipAddress,
			timestamp: new DateTimeImmutable()
		);

		$this->repository()
			->shouldReceive('save')
			->once()
			->with($log);

		// When
		$logger = new AuditEntryLogger($this->repository);
		$logger->__invoke($log);
	}

	private function repository(): AuditLogRepository | MockInterface
	{
		return $this->repository ??= $this->mock(AuditLogRepository::class);
	}
}
