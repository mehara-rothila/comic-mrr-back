<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ComicController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Test route to check if API is working
Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);
});

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public comic routes
Route::get('/comics', [ComicController::class, 'index']);
Route::get('/comics/{comic}', [ComicController::class, 'show']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Protected comic routes
    Route::post('/comics', [ComicController::class, 'store']);
    Route::put('/comics/{comic}', [ComicController::class, 'update']);
    Route::delete('/comics/{comic}', [ComicController::class, 'destroy']);
    
    // Optional: Get comics for authenticated user
    Route::get('/user/comics', [ComicController::class, 'userComics']);
});

// Optional: Route for comic categories
Route::get('/categories', function () {
    return response()->json([
        'categories' => [
            'Action',
            'Adventure',
            'Comedy',
            'Drama',
            'Fantasy',
            'Horror',
            'Mystery',
            'Romance',
            'Sci-Fi',
            'Slice of Life',
            'Superhero'
        ]
    ]);
});

// Error handling for undefined routes
Route::fallback(function () {
    return response()->json([
        'message' => 'Route not found.'
    ], 404);
});