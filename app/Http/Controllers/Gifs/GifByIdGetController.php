<?php

namespace App\Http\Controllers\Gifs;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use LaravelGiphy\Core\Gifs\Application\GifFinder;
use LaravelGiphy\Core\Gifs\Domain\GifsRepository;
use LaravelGiphy\Shared\Domain\Gifs\GifId;

final class GifByIdGetController extends Controller
{
    private readonly GifFinder $gifFinder;

    public function __construct(GifsRepository $repository) {
        $this->gifFinder = new GifFinder($repository);
    }

    public function __invoke(string $id): JsonResponse
    {
        $gif = $this->gifFinder->find(new GifId($id));

        return response()->json(
            $gif->toArray()
        )->setStatusCode($gif->statusCode());
    }
}
