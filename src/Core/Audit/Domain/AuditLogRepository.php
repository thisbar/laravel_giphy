<?php

declare(strict_types=1);

namespace LaravelGhipy\Core\Audit\Domain;

interface AuditLogRepository
{
	public function save(AuditLog $log): void;
}
