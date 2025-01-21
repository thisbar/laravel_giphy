<?php

namespace App\Http\Controllers\Favorites;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use LaravelGiphy\Core\Favorites\Application\FavoriteSaver;
use LaravelGiphy\Core\Favorites\Domain\Favorite;
use LaravelGiphy\Core\Favorites\Domain\FavoriteAlias;
use LaravelGiphy\Core\Favorites\Domain\FavoriteRepository;
use LaravelGiphy\Shared\Domain\Favorites\FavoriteId;
use LaravelGiphy\Shared\Domain\Gifs\GifId;
use LaravelGiphy\Shared\Domain\Users\UserId;

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
