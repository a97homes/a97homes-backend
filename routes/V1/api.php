<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\EndUser\Owner\OwnerController;
use App\Http\Controllers\API\V1\Authentication\LoginController;

Route::post('login', [LoginController::class, 'login']);
Route::post('register/owner', [OwnerController::class, 'registerOwner']);