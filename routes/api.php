<?php

use App\Http\Controllers\Gifs\GifByIdGetController;
use App\Http\Controllers\Gifs\GifsGetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request): mixed {
    return $request->user();
});

Route::prefix('gifs')->group(function() {
    Route::get('/search', GifsGetController::class);
    Route::get('/{id}', GifByIdGetController::class);
});
