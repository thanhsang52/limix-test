<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;

Route::post('login', [AuthController::class, 'login'])->withoutMiddleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('users', UserController::class);
    Route::apiResource('posts', PostController::class);
});
