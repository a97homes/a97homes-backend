<?php

use App\Http\Controllers\API\V1\Authentication\LoginController;
use App\Http\Controllers\API\V1\EndUser\RegisterController;
use Illuminate\Support\Facades\Route;

Route::post('login', [LoginController::class, 'login']);
Route::post('register/owner', [RegisterController::class, 'register']);
