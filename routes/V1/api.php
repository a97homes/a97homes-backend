<?php

use App\Http\Controllers\API\V1\Authentication\LoginController;
use App\Http\Controllers\API\V1\EndUser\AttributeController;
use App\Http\Controllers\API\V1\EndUser\CityController;
use App\Http\Controllers\API\V1\EndUser\CompanyInfoController;
use App\Http\Controllers\API\V1\EndUser\CompoundController;
use App\Http\Controllers\API\V1\EndUser\ContactController;
use App\Http\Controllers\API\V1\EndUser\CountryController;
use App\Http\Controllers\API\V1\EndUser\DeveloperController;
use App\Http\Controllers\API\V1\EndUser\FavoriteController;
use App\Http\Controllers\API\V1\EndUser\HomeController;
use App\Http\Controllers\API\V1\EndUser\PropertyController;
use App\Http\Controllers\API\V1\EndUser\PropertyFavoriteController;
use App\Http\Controllers\API\V1\EndUser\PropertyTypeController;
use App\Http\Controllers\API\V1\EndUser\RegisterController;
use App\Http\Controllers\API\V1\EndUser\StateController;
use Illuminate\Support\Facades\Route;

Route::post('login', [LoginController::class, 'login']);
Route::post('register', [RegisterController::class, 'register']);

// =========================Homepage==========================
Route::get('offers', [HomeController::class, 'offers']);
Route::get('banners', [HomeController::class, 'banners']);
Route::get('latest-projects', [HomeController::class, 'latestProjects']);
Route::get('popular-areas', [HomeController::class, 'popularAreas']);
Route::get('featured-compounds', [HomeController::class, 'featuredCompounds']);
Route::get('featured-properties', [HomeController::class, 'featuredProperties']);
// =========================Homepage==========================

// =========================Location==========================
Route::get('countries', [CountryController::class, 'index']);
Route::get('countries/{country}/states', [CountryController::class, 'states']);
Route::get('states/{state}/cities', [StateController::class, 'cities']);
Route::get('cities/popular', [CityController::class, 'popular']);
// =========================Location==========================

// =========================Dropdowns==========================
Route::get('developers/dropdown', [DeveloperController::class, 'dropdown']);
Route::get('property-types/dropdown', [PropertyTypeController::class, 'dropdown']);
// =========================Dropdowns==========================

// =========================Developers==========================
Route::get('developers', [DeveloperController::class, 'index']);
Route::get('developers/{developer}', [DeveloperController::class, 'show']);
// =========================Developers==========================

// =========================Filters==========================
Route::get('attributes/filterable', [AttributeController::class, 'filterable']);
Route::get('attributes/{attribute}/options', [AttributeController::class, 'options']);
Route::get('property-types/{propertyType}/attributes', [PropertyTypeController::class, 'attributes']);
// =========================Filters==========================

// =========================Properties==========================
Route::get('properties', [PropertyController::class, 'index']);
Route::get('properties/compare', [PropertyController::class, 'compare']);
Route::get('properties/{property}', [PropertyController::class, 'show']);
// =========================Properties==========================

// =========================Compounds==========================
Route::get('compounds', [CompoundController::class, 'index']);
Route::get('compounds/compare', [CompoundController::class, 'compare']);
Route::get('compounds/{compound}', [CompoundController::class, 'show']);
// =========================Compounds==========================

// =========================Contact==========================
Route::post('contact', [ContactController::class, 'store']);
// =========================Contact==========================

// =========================Company-Info==========================
Route::get('company-info', [CompanyInfoController::class, 'show']);
// =========================Company-Info==========================

// =========================Favorites==========================
Route::middleware('auth:sanctum')->group(function () {
    Route::get('favorites', [FavoriteController::class, 'index']);
    Route::post('favorites', [FavoriteController::class, 'store']);
    Route::delete('favorites/{compound}', [FavoriteController::class, 'destroy']);

    Route::get('property-favorites', [PropertyFavoriteController::class, 'index']);
    Route::post('property-favorites', [PropertyFavoriteController::class, 'store']);
    Route::delete('property-favorites/{property}', [PropertyFavoriteController::class, 'destroy']);
});
// =========================Favorites==========================
