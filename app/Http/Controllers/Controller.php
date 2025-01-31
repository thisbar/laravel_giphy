<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function successResponse(array $data, int $statusCode = 200): JsonResponse
    {
        return response()->json($data, $statusCode);
    }

    protected function errorResponse(string $message, int $statusCode): JsonResponse
    {
        return response()->json(['error' => $message], $statusCode);
    }
}
