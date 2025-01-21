<?php

declare(strict_types=1);

namespace LaravelGhipy\Core\Audit\Domain;

use DateTimeImmutable;

final class AuditLog
{
	public function __construct(
		private string $userId,
		private string $service,
		private array $requestBody,
		private int $httpStatusCode,
		private array $responseBody,
		private string $ipAddress,
		private DateTimeImmutable $timestamp
	) {}

	public static function create(
		string $userId,
		string $service,
		array $requestBody,
		int $httpStatusCode,
		array $responseBody,
		string $ipAddress
	): self {
		return new self(
			$userId,
			$service,
			$requestBody,
			$httpStatusCode,
			$responseBody,
			$ipAddress,
			new DateTimeImmutable()
		);
	}

	public function toArray(): array
	{
		return [
			'user_id'          => $this->userId,
			'service'          => $this->service,
			'request_body'     => $this->sanitize($this->requestBody),
			'http_status_code' => $this->httpStatusCode,
			'response_body'    => $this->sanitize($this->responseBody),
			'ip_address'       => $this->ipAddress,
			'@timestamp'       => $this->timestamp->format(DATE_ATOM),
		];
	}

	private function sanitize(array $data): array
	{
		$sensitiveKeys = ['password', 'token'];
		foreach ($sensitiveKeys as $key) {
			if (array_key_exists($key, $data)) {
				$data[$key] = '***';
			}
		}
		return $data;
	}
}
