<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Passport\PersonalAccessTokenFactory;
use LaravelGhipy\Core\Auth\Infrastructure\TokenGenerator;
use LaravelGhipy\Core\Users\Application\UserAuthenticator;
use LaravelGhipy\Core\Users\Domain\Email;
use LaravelGhipy\Core\Users\Domain\UserRepository;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

final class PostAuthController extends Controller
{
    private UserAuthenticator $authenticator;
    private TokenGenerator $tokenGenerator;

    public function __construct(
        UserRepository $userRepository,
        PersonalAccessTokenFactory $tokenFactory,
    ) {
        $this->authenticator  = new UserAuthenticator($userRepository);
        $this->tokenGenerator = new TokenGenerator($tokenFactory);
    }

    public function __invoke(Request $request): JsonResponse
    {
        $this->validateRequest($request);

        $user = $this->authenticator->auth(
            Email::from($request->input('email')),
            $request->input('password')
        );

        if (!$user) {
            return $this->errorResponse('Invalid credentials', ResponseAlias::HTTP_UNAUTHORIZED);
        }

        try {
            $tokenData = $this->tokenGenerator->generate($user);

            return $this->successResponse($tokenData);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to generate token', ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function validateRequest(Request $request): void
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    }
}
