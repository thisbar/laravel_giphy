<?php

namespace App\Http\Controllers\Favorites;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use LaravelGhipy\Core\Favorites\Application\FavoriteSaver;
use LaravelGhipy\Core\Favorites\Domain\Favorite;
use LaravelGhipy\Core\Favorites\Domain\FavoriteAlias;
use LaravelGhipy\Core\Favorites\Domain\FavoriteRepository;
use LaravelGhipy\Shared\Domain\Favorites\FavoriteId;
use LaravelGhipy\Shared\Domain\Gifs\GifId;
use LaravelGhipy\Shared\Domain\Users\UserId;

final class PostFavoriteController extends Controller
{
    private FavoriteSaver $favoriteSaver;

    public function __construct(
        FavoriteRepository $favoriteRepository,
    ) {
        $this->favoriteSaver = new FavoriteSaver($favoriteRepository);
    }

    public function __invoke(Request $request): JsonResponse
    {
        $this->validateRequest($request);

        $favorite = new Favorite(
            FavoriteId::random(),
            GifId::from($request->input('gif_id')),
            FavoriteAlias::from($request->input('alias')),
            UserId::from($request->input('user_id'))
        );

        $this->favoriteSaver->save(
            $favorite
        );

        return $this->successResponseWithMessage($favorite);
    }

    private function successResponseWithMessage(Favorite $favorite): JsonResponse {
        return $this->successResponse(['message' => 'GIF saved as favorite', 'data' => $favorite->toArray()], Response::HTTP_CREATED);
    }

    private function validateRequest(Request $request): void
    {
        $request->validate([
            'gif_id' => 'required|string',
            'alias' => 'required|string',
            'user_id' => 'required|string',
        ]);
    }
}
