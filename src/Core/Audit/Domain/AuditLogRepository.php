<?php

declare(strict_types=1);

namespace LaravelGiphy\Core\Audit\Domain;

interface AuditLogRepository
{
	public function save(AuditLog $log): void;
}
