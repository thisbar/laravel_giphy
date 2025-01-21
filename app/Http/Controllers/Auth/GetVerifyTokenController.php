<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Passport\TokenRepository;
use LaravelGiphy\Core\Auth\Infrastructure\TokenValidator;
use LaravelGiphy\Core\Users\Domain\UserRepository;
use Lcobucci\JWT\Configuration;

final class GetVerifyTokenController extends Controller
{
    private TokenValidator $tokenValidator;

    public function __construct(
        Configuration $jwtConfiguration,
        TokenRepository $tokenRepository,
        UserRepository $userRepository,
    ) {
        $this->tokenValidator = new TokenValidator($jwtConfiguration, $tokenRepository, $userRepository);
    }

    public function __invoke(Request $request): JsonResponse
    {
        $token = $request->bearerToken();

        if ($token === null) {
            return $this->errorResponse('Token missing', Response::HTTP_BAD_REQUEST);
        }

        $userData = $this->tokenValidator->validate($token);

        if ($userData === null) {
            return $this->errorResponse('Invalid or expired token', Response::HTTP_UNAUTHORIZED);
        }

        return $this->successResponse($userData);
    }
}
