<?php

use App\Http\Controllers\API\V1\Admin\CityController;
use App\Http\Controllers\API\V1\Admin\CountryController;
use App\Http\Controllers\API\V1\Admin\RoleController;
use App\Http\Controllers\API\V1\Admin\StateController;
use Illuminate\Support\Facades\Route;

Route::apiResource('roles', RoleController::class);
// ==========================country==========================
Route::get('countries/dropdown', [CountryController::class, 'dropdown']);
Route::apiResource('countries', CountryController::class);
// ==========================country==========================

// ==========================state=========================
Route::get('states/dropdown', [StateController::class, 'dropdown']);
Route::apiResource('states', StateController::class);
// ==========================state=========================

// ==========================state=========================
Route::get('cities/dropdown', [CityController::class, 'dropdown']);
Route::apiResource('cities', CityController::class);
// ==========================state=========================
