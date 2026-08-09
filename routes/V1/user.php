
<?php

use App\Http\Controllers\API\V1\EndUser\OrderController;
use App\Http\Controllers\API\V1\EndUser\ProfileController;
use App\Http\Controllers\API\V1\EndUser\SocialController;
use Illuminate\Support\Facades\Route;

Route::post('orders', [OrderController::class, 'store'])->withoutMiddleware('auth:sanctum');

// =========================End-User-Socials===========
Route::get('socials', [SocialController::class, 'index']);

// =========================End-User-Profile===========
Route::middleware('auth:sanctum')->prefix('profile')->group(function () {
    Route::get('/', [ProfileController::class, 'show']);
    Route::put('/', [ProfileController::class, 'update']);
    Route::put('/password', [ProfileController::class, 'updatePassword']);
    Route::post('/avatar', [ProfileController::class, 'updateAvatar']);
    Route::delete('/avatar', [ProfileController::class, 'deleteAvatar']);
});
