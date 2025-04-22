<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;

Route::apiResource('users', UserController::class);
Route::apiResource('posts', PostController::class);