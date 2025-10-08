
<?php

use App\Http\Controllers\API\V1\EndUser\ContactController;
use App\Http\Controllers\API\V1\EndUser\OrderController;
use Illuminate\Support\Facades\Route;

Route::post('orders', [OrderController::class, 'store'])->withoutMiddleware('auth:sanctum');
// =========================End-User-Contact===========
Route::post('contact', [ContactController::class, 'store']);
