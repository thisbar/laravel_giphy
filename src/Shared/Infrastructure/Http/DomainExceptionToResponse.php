<?php

declare(strict_types=1);

namespace LaravelGhipy\Shared\Infrastructure\Http;

use LaravelGhipy\Shared\Domain\DomainError;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DomainExceptionToResponse
{
	public static function toResponse(DomainError $exception): JsonResponse
	{
		return response()->json(['error' => $exception->getMessage()], $exception->getCode());
	}
}
