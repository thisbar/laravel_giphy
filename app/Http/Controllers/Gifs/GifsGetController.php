<?php

namespace App\Http\Controllers\Gifs;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use LaravelGhipy\Core\Gifs\Application\AllGifsSearcher;
use LaravelGhipy\Core\Gifs\Domain\GifsRepository;
use LaravelGhipy\Shared\Domain\ValueObject\Search\Limit;
use LaravelGhipy\Shared\Domain\ValueObject\Search\Offset;
use LaravelGhipy\Shared\Domain\ValueObject\Search\SearchQuery;

final class GifsGetController extends Controller
{
    private readonly AllGifsSearcher $gifsSearcher;

    public function __construct(GifsRepository $repository) {
        $this->gifsSearcher = new AllGifsSearcher($repository);
    }

    public function __invoke(Request $request): JsonResponse
    {
        $gifs = $this->gifsSearcher->search(
            new SearchQuery($request->query('query')),
            new Limit($request->query('limit')),
            new Offset($request->query('offset'))
        );

        return response()->json(
            $gifs->toArray()
        )->setStatusCode($gifs->statusCode());
    }
}

