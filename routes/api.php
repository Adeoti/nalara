<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\BookmarkController;
use App\Http\Controllers\Api\ReactionController;
use App\Http\Controllers\Api\NewsCourceController;
use App\Http\Controllers\Api\NewsCategoryController;
use App\Http\Controllers\Api\UserInterestController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Outside the AUTH Gate
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::get('/news/{news}/comments', [NewsController::class, 'getComments']); //Working fine
Route::get('/reactions/{type}/{id}', [ReactionController::class, 'getReactions']); //Working fine



// Inside the AUTH Gate
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/news', [NewsController::class, 'index']); //Working fine
    Route::get('/news/from-followed-sources', [NewsController::class, 'newsFromFollowedSources']); //Working fine


    Route::get('/news-sources', [NewsCourceController::class, 'allSources']); //Working fine
    Route::get('/news-source/{source}', [NewsCourceController::class, 'newsBySource']); //Working fine
    Route::get('/followed-sources', [NewsCourceController::class, 'followedSources']); //Working fine



    Route::post('/follow-source/{source}', [NewsCourceController::class, 'toggleFollow']); //Working fine


    Route::post('/news/comments', [NewsController::class, 'addComment']); //Working fine

    Route::post('/react', [ReactionController::class, 'react']); //Working fine

    Route::post('/user/interests', [UserInterestController::class, 'store']); // Working fine

    Route::get('/news-categories', [NewsCategoryController::class, 'index']); // Working fine




    Route::post('/bookmark/{news}', [BookmarkController::class, 'toggle']); // Save or unsave
    Route::get('/bookmarks', [BookmarkController::class, 'myBookmarks']);
});
