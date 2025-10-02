<?php

use App\Http\Controllers\API\V1\Admin\AttributeController;
use App\Http\Controllers\API\V1\Admin\CityController;
use App\Http\Controllers\API\V1\Admin\CountryController;
use App\Http\Controllers\API\V1\Admin\PropertyController;
use App\Http\Controllers\API\V1\Admin\PropertyTypeController;
use App\Http\Controllers\API\V1\Admin\RoleController;
use App\Http\Controllers\API\V1\Admin\StateController;
use App\Http\Controllers\API\V1\Admin\UnitController;
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

// ==========================city=========================
Route::get('cities/dropdown', [CityController::class, 'dropdown']);
Route::apiResource('cities', CityController::class);
// ==========================city=========================

// ==========================property-type=========================
Route::get('property-types/dropdown', [PropertyTypeController::class, 'dropdown']);
Route::apiResource('property-types', PropertyTypeController::class)->parameters([
    'property-types' => 'propertyType',
]);
// =========================property-type=========================

// =========================attribute=========================
Route::get('attributes/dropdown', [AttributeController::class, 'dropdown']);
Route::apiResource('attributes', AttributeController::class);
// =========================attribute=========================

// =========================unit=========================
Route::get('units-dropdown', [UnitController::class, 'dropdown']);
Route::apiResource('units', UnitController::class);
// =========================unit=========================

// =========================property=========================
Route::get('properties-dropdown', [PropertyController::class, 'dropdown']);
Route::apiResource('properties', PropertyController::class);
// =========================property=========================
