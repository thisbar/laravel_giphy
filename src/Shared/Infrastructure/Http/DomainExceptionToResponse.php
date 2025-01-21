<?php

declare(strict_types=1);

namespace LaravelGiphy\Shared\Infrastructure\Http;

use LaravelGiphy\Shared\Domain\DomainError;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DomainExceptionToResponse
{
	public static function toResponse(DomainError $exception): JsonResponse
	{
		return response()->json(['error' => $exception->getMessage()], $exception->errorCode());
	}
}
