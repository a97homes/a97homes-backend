<?php

use App\Http\Controllers\API\V1\Admin\AttributeController;
use App\Http\Controllers\API\V1\Admin\CityController;
use App\Http\Controllers\API\V1\Admin\CountryController;
use App\Http\Controllers\API\V1\Admin\OrderController;
use App\Http\Controllers\API\V1\Admin\PermissionController;
use App\Http\Controllers\API\V1\Admin\PropertyController;
use App\Http\Controllers\API\V1\Admin\PropertyTypeController;
use App\Http\Controllers\API\V1\Admin\RoleController;
use App\Http\Controllers\API\V1\Admin\SocialController;
use App\Http\Controllers\API\V1\Admin\StateController;
use App\Http\Controllers\API\V1\Admin\UnitController;
use App\Http\Controllers\API\V1\Admin\UserController;
use Illuminate\Support\Facades\Route;

// ==========================Role==========================
Route::post('roles/{role}/assign-permissions', [RoleController::class, 'assignPermissions']);
Route::get('roles/dropdown', [RoleController::class, 'dropdown']);
Route::apiResource('roles', RoleController::class);
// ==========================Role==========================

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
Route::get('units/dropdown', [UnitController::class, 'dropdown']);
Route::apiResource('units', UnitController::class);
// =========================unit=========================

// =========================property=========================
Route::get('properties/dropdown', [PropertyController::class, 'dropdown']);
Route::patch('properties/{property}/status', [PropertyController::class, 'updateStatus']);
Route::apiResource('properties', PropertyController::class);
Route::post('properties/{property}/media', [PropertyController::class, 'addMedia']);
Route::delete('properties/{property}/media/{media}', [PropertyController::class, 'deleteMediaAction']);
// =========================property=========================

// ========================permission=====================
Route::get('permissions/dropdown ', [PermissionController::class, 'dropdown']);
Route::apiResource('permissions', PermissionController::class);
// ========================permission=====================

// ========================Order=====================
Route::get('orders', [OrderController::class, 'index']);
Route::get('orders/{order}', [OrderController::class, 'show']);
Route::patch('orders/{order}/approve', [OrderController::class, 'approve']);
Route::patch('orders/{order}/reject', [OrderController::class, 'reject']);
// ========================Order=====================

// ================User====================
Route::put('users/{user}/update-roles', [UserController::class, 'updateRoles']);
Route::post('users/{user}/assign-roles', [UserController::class, 'assignRoles']);
Route::apiResource('users', UserController::class);
// ================User====================

// ===============social====================
Route::apiResource('socials', SocialController::class)->except(['update']);
// ===============social====================
