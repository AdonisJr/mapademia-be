<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\FavoriteController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserController::class, 'index']);
    Route::apiResource('/users', UserController::class);
    Route::put('/changePassword', [UserController::class, 'changePassword']);

    // Business
    Route::apiResource('/businesses', BusinessController::class);
    Route::put('/updateBusinesses/{businessId}',[ BusinessController::class, 'updateBusiness']);

    // Category
    Route::apiResource('/category', CategoryController::class);

    //feedback
    // Route to submit feedback for a business
    Route::post('/feedback/{businessId}', [FeedbackController::class, 'store']);

    // Route to get feedback for a business
    Route::get('/feedback/{businessId}', [FeedbackController::class, 'show']);

    // Optional: Route to delete feedback
    Route::delete('/feedback/{feedbackId}', [FeedbackController::class, 'destroy']);

    // favorite

    Route::get('/favorites', [FavoriteController::class, 'getFavorites']); // Retrieve favorites
    Route::post('/favorites', [FavoriteController::class, 'storeFavorite']); // Add to favorites
    Route::delete('/favorites/{businessId}', [FavoriteController::class, 'removeFavorite']); // Remove from favorites

    // Other protected routes
    
});

// Create API routes for User CRUD

