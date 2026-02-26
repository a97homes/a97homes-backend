<?php

use App\Http\Controllers\API\V1\Authentication\LoginController;
use App\Http\Controllers\API\V1\EndUser\AttributeController;
use App\Http\Controllers\API\V1\EndUser\CityController;
use App\Http\Controllers\API\V1\EndUser\CountryController;
use App\Http\Controllers\API\V1\EndUser\DeveloperController;
use App\Http\Controllers\API\V1\EndUser\PropertyController;
use App\Http\Controllers\API\V1\EndUser\PropertyTypeController;
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

// =========================Dropdowns==========================
Route::get('developers/dropdown', [DeveloperController::class, 'dropdown']);
Route::get('property-types/dropdown', [PropertyTypeController::class, 'dropdown']);
Route::get('cities/dropdown', [CityController::class, 'dropdown']);
// =========================Dropdowns==========================

// =========================Filters==========================
Route::get('attributes/filterable', [AttributeController::class, 'filterable']);
Route::get('attributes/{attribute}/options', [AttributeController::class, 'options']);
Route::get('property-types/{propertyType}/attributes', [PropertyTypeController::class, 'attributes']);
// =========================Filters==========================

// =========================Properties==========================
Route::get('properties', [PropertyController::class, 'index']);
Route::get('properties/{property}', [PropertyController::class, 'show']);
// =========================Properties==========================
