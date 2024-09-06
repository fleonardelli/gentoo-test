<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Middleware\QueryParamsMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/posts', [PostController::class, 'index'])->middleware([QueryParamsMiddleware::class]);

// I would not expose IDs here, I'd use UUIDs instead.
Route::delete('/posts/{id}', [PostController::class, 'delete']);

Route::get('/comments', [CommentController::class, 'index'])->middleware([QueryParamsMiddleware::class]);

Route::delete('/comments/{id}', [CommentController::class, 'delete']);
