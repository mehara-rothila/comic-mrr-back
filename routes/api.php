<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ComicController;
use App\Http\Controllers\API\AdminController;

// Public routes that don't require authentication
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/comics', [ComicController::class, 'index']);
Route::get('/comics/featured', [ComicController::class, 'featured']);
Route::get('/comics/{comic}', [ComicController::class, 'show']);
Route::get('/categories', function () {
    return response()->json([
        'categories' => [
            'Action', 'Adventure', 'Comedy', 'Drama', 'Fantasy',
            'Horror', 'Mystery', 'Romance', 'Sci-Fi', 'Slice of Life', 'Superhero'
        ]
    ]);
});

// Routes that require authentication
Route::middleware('auth:sanctum')->group(function () {
    // Test route for admin middleware
    Route::get('/test-admin', function () {
        return response()->json(['message' => 'Admin middleware is working']);
    })->middleware(\App\Http\Middleware\AdminMiddleware::class);

    // General authenticated routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::get('/user/comics', [ComicController::class, 'userComics']);
    Route::get('/check-admin', [AuthController::class, 'checkAdmin']);

    // Admin-only routes
    Route::middleware(\App\Http\Middleware\AdminMiddleware::class)
        ->prefix('admin')
        ->group(function () {
            Route::get('/comics', [AdminController::class, 'index']);
            Route::get('/stats', [AdminController::class, 'stats']);
            Route::post('/comics', [AdminController::class, 'storeComic']);
            Route::put('/comics/{comic}', [AdminController::class, 'updateComic']);
            Route::delete('/comics/{comic}', [AdminController::class, 'deleteComic']);
            Route::get('/users', [AdminController::class, 'users']);
        });
});

// Fallback route for undefined routes
Route::fallback(function () {
    return response()->json(['message' => 'Route not found.'], 404);
});