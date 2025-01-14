<?php

namespace App\Http\Controllers\Gifs;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use LaravelGhipy\Core\Gifs\Application\GifFinder;
use LaravelGhipy\Core\Gifs\Domain\GifsRepository;
use LaravelGhipy\Shared\Domain\Gifs\GifId;

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
