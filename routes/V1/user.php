
<?php

use App\Http\Controllers\API\V1\EndUser\OrderController;
use App\Http\Controllers\API\V1\EndUser\SellUnitController;
use App\Http\Controllers\API\V1\EndUser\SocialController;
use Illuminate\Support\Facades\Route;

Route::post('orders', [OrderController::class, 'store'])->withoutMiddleware('auth:sanctum');
// =========================End-User-Sell-Unit===========
Route::post('sell-units', [SellUnitController::class, 'store']);

// =========================End-User-Socials===========
Route::get('socials', [SocialController::class, 'index']);

