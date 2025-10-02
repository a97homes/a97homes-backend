<?php

use App\Http\Controllers\API\V1\Authentication\LoginController;
use Illuminate\Support\Facades\Route;

Route::post('login', [LoginController::class, 'login']);
