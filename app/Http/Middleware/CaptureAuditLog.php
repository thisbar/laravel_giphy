<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use LaravelGiphy\Core\Audit\Application\AuditEntryLogger;
use LaravelGiphy\Core\Audit\Domain\AuditLog;
use Symfony\Component\HttpFoundation\Response;

final class CaptureAuditLog
{
    public function __construct(private AuditEntryLogger $auditEntryLogger) {}

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $user = Auth::user();
        $userId = $user?->getAuthIdentifier() ?? 'guest';
        $service = $request->path();
        $requestBody = $request->all();
        $httpStatusCode = $response->status();
        $responseBody = json_decode($response->getContent(), true) ?? [];
        $ipAddress = $request->ip() ?? '0.0.0.0';

        $log = AuditLog::create($userId, $service, $requestBody, $httpStatusCode, $responseBody, $ipAddress);
        $this->auditEntryLogger->__invoke($log  );

        return $response;
    }
}
