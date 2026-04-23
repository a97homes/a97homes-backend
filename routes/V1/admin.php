<?php

use App\Http\Controllers\API\V1\Admin\ArticleController;
use App\Http\Controllers\API\V1\Admin\AttributeController;
use App\Http\Controllers\API\V1\Admin\CityController;
use App\Http\Controllers\API\V1\Admin\CompanyInfoController;
use App\Http\Controllers\API\V1\Admin\CompoundController;
use App\Http\Controllers\API\V1\Admin\ConsultantController;
use App\Http\Controllers\API\V1\Admin\ContactController;
use App\Http\Controllers\API\V1\Admin\CountryController;
use App\Http\Controllers\API\V1\Admin\DeveloperController;
use App\Http\Controllers\API\V1\Admin\FaqController;
use App\Http\Controllers\API\V1\Admin\NewsletterSubscriberController;
use App\Http\Controllers\API\V1\Admin\OrderController;
use App\Http\Controllers\API\V1\Admin\PageController;
use App\Http\Controllers\API\V1\Admin\PaymentPlanController;
use App\Http\Controllers\API\V1\Admin\PermissionController;
use App\Http\Controllers\API\V1\Admin\PhaseController;
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
Route::apiResource('properties', PropertyController::class);
Route::get('properties/dropdown', [PropertyController::class, 'dropdown']);
Route::patch('properties/{property}/status', [PropertyController::class, 'updateStatus']);
Route::post('properties/{property}/media', [PropertyController::class, 'addMedia']);
Route::delete('properties/{property}/media/{media}', [PropertyController::class, 'deleteMediaAction']);
// =========================property=========================

// ========================permission=====================
Route::get('permissions/dropdown', [PermissionController::class, 'dropdown']);
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

// =====================Developer Routes============
Route::get('developers/dropdown', [DeveloperController::class, 'dropdown']);
Route::apiResource('developers', DeveloperController::class);
// =====================Developer Routes============

// ======================Compound Routes=================
Route::get('compounds/dropdown', [CompoundController::class, 'dropdown']);
Route::apiResource('compounds', CompoundController::class);
Route::post('compounds/{compound}/media', [CompoundController::class, 'addMedia']);
Route::delete('compounds/{compound}/media/{media}', [CompoundController::class, 'deleteMedia']);
// ======================Compound Routes=================

// ======================Consultant Routes==================
Route::get('consultants/dropdown', [ConsultantController::class, 'dropdown']);
Route::apiResource('consultants', ConsultantController::class);
// ======================Consultant Routes==================

// ======================Contact Routes==================
Route::apiResource('contacts', ContactController::class)->only(['index', 'show', 'destroy']);
// ======================Contact Routes==================

// ======================Company Info Routes==================
Route::get('company-info', [CompanyInfoController::class, 'show']);
Route::put('company-info', [CompanyInfoController::class, 'update']);
// ======================Company Info Routes==================

// ======================Payment Plan Routes==================
Route::apiResource('payment-plans', PaymentPlanController::class)->parameters([
    'payment-plans' => 'paymentPlan',
]);
// ======================Payment Plan Routes==================

// ======================Faq Routes==================
Route::apiResource('faqs', FaqController::class);
// ======================Faq Routes==================

// ======================Article Routes==================
Route::patch('articles/{article}/toggle-publish', [ArticleController::class, 'togglePublish']);
Route::apiResource('articles', ArticleController::class);
// ======================Article Routes==================

// ======================Newsletter Subscriber Routes==================
Route::apiResource('newsletter-subscribers', NewsletterSubscriberController::class)->only(['index', 'show', 'destroy'])->parameters([
    'newsletter-subscribers' => 'newsletterSubscriber',
]);
// ======================Newsletter Subscriber Routes==================

// ======================Static Pages Routes==================
Route::apiResource('pages', PageController::class);
// ======================Static Pages Routes==================

// ======================Phase Routes==================
Route::get('phases/dropdown', [PhaseController::class, 'dropdown']);
Route::apiResource('phases', PhaseController::class);
// ======================Phase Routes==================
