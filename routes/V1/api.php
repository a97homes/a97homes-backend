<?php

use App\Http\Controllers\API\V1\Authentication\LoginController;
use App\Http\Controllers\API\V1\EndUser\CountryController;
use App\Http\Controllers\API\V1\EndUser\RegisterController;
use App\Http\Controllers\API\V1\EndUser\StateController;
use Illuminate\Support\Facades\Route;

Route::post('login', [LoginController::class, 'login']);
Route::post('register/owner', [RegisterController::class, 'register']);

// =========================Location==========================
Route::get('countries', [CountryController::class, 'index']);
Route::get('countries/{country}/states', [CountryController::class, 'states']);
Route::get('states/{state}/cities', [StateController::class, 'cities']);
// =========================Location==========================
// =========================End-User-Developers-Dropdown===========
Route::get('developers/dropdown', [DeveloperController::class, 'dropdown']);
