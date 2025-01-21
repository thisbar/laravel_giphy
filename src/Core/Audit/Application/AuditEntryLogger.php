<?php

declare(strict_types=1);

namespace LaravelGhipy\Core\Audit\Application;

use LaravelGhipy\Core\Audit\Domain\AuditLog;
use LaravelGhipy\Core\Audit\Domain\AuditLogRepository;

final class AuditEntryLogger
{
	public function __construct(private AuditLogRepository $repository) {}

	public function __invoke(AuditLog $log): void
	{
		$this->repository->save($log);
	}
}
