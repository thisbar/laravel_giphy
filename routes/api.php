<?php

use App\Http\Controllers\Auth\GetVerifyTokenController;
use App\Http\Controllers\Auth\PostAuthController;
use App\Http\Controllers\Favorites\PostFavoriteController;
use App\Http\Controllers\Gifs\GifByIdGetController;
use App\Http\Controllers\Gifs\GifsGetController;
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

Route::prefix('auth')->group(function() {
    Route::post('/login', PostAuthController::class);
    Route::get('/verify-token', GetVerifyTokenController::class);
});

Route::middleware('auth:api')->group(function () {
    Route::prefix('gifs')->group(function() {
        Route::get('/search', GifsGetController::class);
        Route::get('/{id}', GifByIdGetController::class);
    });

    Route::prefix('favorites')->group(function() {
        Route::post('/', PostFavoriteController::class);
    });
});


