<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'auth'], function () {

    Route::middleware(['auth:sanctum'])->group(function (){

        

    });

    Route::post('/users/generate-auth-token/{userId}', [Src\Authorization\Users\Infrastructure\PostGenerateAuthTokenByUserId::class, '__invoke']);

});


Route::group(['prefix' => 'blog'], function () {

    Route::middleware(['auth:sanctum'])->group(function (){

        Route::get('/articles/all', [Src\Blog\Articles\Infrastructure\GetAllArticlesController::class, '__invoke']);
        Route::get('/articles/{articleId}', [Src\Blog\Articles\Infrastructure\GetArticleController::class, '__invoke']);
        Route::post('/articles/create-or-update', [Src\Blog\Articles\Infrastructure\PostCreateOrUpdateArticleController::class, '__invoke']);        

    });

    

});
