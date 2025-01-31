<?php

declare(strict_types=1);

namespace LaravelGiphy\Core\Audit\Application;

use LaravelGiphy\Core\Audit\Domain\AuditLog;
use LaravelGiphy\Core\Audit\Domain\AuditLogRepository;

final class AuditEntryLogger
{
	public function __construct(private AuditLogRepository $repository) {}

	public function __invoke(AuditLog $log): void
	{
		$this->repository->save($log);
	}
}
