<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\SkillController;
use App\Http\Controllers\Api\ForgotPasswordController;
use App\Http\Controllers\Api\ResetPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// public Routes
Route::post("/register", [AuthController::class, "register"]);
Route::post("/login", [AuthController::class, "login"]);

// public GET
Route::get('/projects',      [ProjectController::class, 'index']);
Route::get('/projects/{project}', [ProjectController::class, 'show']);
Route::get('/skills',        [SkillController::class, 'index']);
Route::get('/skills/{skill}',     [SkillController::class, 'show']);
Route::get('/posts',         [PostController::class, 'index']);
Route::get('/posts/{post}',       [PostController::class, 'show']);
Route::get('/search', SearchController::class);

// password handle
Route::post('/forgot-password', ForgotPasswordController::class);
Route::post('/reset-password',  ResetPasswordController::class);

// protected Routes (need token)
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me',      [AuthController::class, 'me']);

    // Hanya authenticated user yang bisa CUD
    Route::post('/projects',              [ProjectController::class, 'store']);
    Route::put('/projects/{project}',     [ProjectController::class, 'update']);
    Route::delete('/projects/{project}',  [ProjectController::class, 'destroy']);

    Route::post('/skills',                [SkillController::class, 'store']);
    Route::put('/skills/{skill}',         [SkillController::class, 'update']);
    Route::delete('/skills/{skill}',      [SkillController::class, 'destroy']);

    Route::post('/posts',                 [PostController::class, 'store']);
    Route::put('/posts/{post}',           [PostController::class, 'update']);
    Route::delete('/posts/{post}',        [PostController::class, 'destroy']);
});
