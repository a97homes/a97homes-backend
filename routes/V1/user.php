
<?php

use App\Http\Controllers\API\V1\EndUser\CityController;
use App\Http\Controllers\API\V1\EndUser\ContactController;
use App\Http\Controllers\API\V1\EndUser\OrderController;
use App\Http\Controllers\API\V1\EndUser\SocialController;
use Illuminate\Support\Facades\Route;

Route::post('orders', [OrderController::class, 'store'])->withoutMiddleware('auth:sanctum');
// =========================End-User-Contact===========
Route::post('contact', [ContactController::class, 'store']);

// =========================End-User-popular-cities===========
Route::get('cities/popular', [CityController::class, 'popular']);

// =========================End-User-Socials===========
Route::apiResource('socials', SocialController::class); // TODO:: adding except
