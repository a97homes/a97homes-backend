
<?php

use App\Http\Controllers\API\V1\EndUser\OrderController;
use Illuminate\Support\Facades\Route;

Route::post('user-requests', [OrderController::class, 'store']);
