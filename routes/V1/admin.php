<?php

use App\Http\Controllers\API\V1\Admin\ArticleController;
use App\Http\Controllers\API\V1\Admin\AttributeController;
use App\Http\Controllers\API\V1\Admin\BannerController;
use App\Http\Controllers\API\V1\Admin\ChatbotConversationController;
use App\Http\Controllers\API\V1\Admin\CityController;
use App\Http\Controllers\API\V1\Admin\CompanyInfoController;
use App\Http\Controllers\API\V1\Admin\CompoundController;
use App\Http\Controllers\API\V1\Admin\CompoundReviewController;
use App\Http\Controllers\API\V1\Admin\ConsultantController;
use App\Http\Controllers\API\V1\Admin\ConsultantReviewController;
use App\Http\Controllers\API\V1\Admin\ContactController;
use App\Http\Controllers\API\V1\Admin\CountryController;
use App\Http\Controllers\API\V1\Admin\DashboardController;
use App\Http\Controllers\API\V1\Admin\DeveloperController;
use App\Http\Controllers\API\V1\Admin\DiscountController;
use App\Http\Controllers\API\V1\Admin\FaqController;
use App\Http\Controllers\API\V1\Admin\NewsletterCampaignController;
use App\Http\Controllers\API\V1\Admin\NewsletterSubscriberController;
use App\Http\Controllers\API\V1\Admin\NotificationBroadcastController;
use App\Http\Controllers\API\V1\Admin\OfferController;
use App\Http\Controllers\API\V1\Admin\OrderController;
use App\Http\Controllers\API\V1\Admin\PageController;
use App\Http\Controllers\API\V1\Admin\PaymentPlanController;
use App\Http\Controllers\API\V1\Admin\PermissionController;
use App\Http\Controllers\API\V1\Admin\PhaseController;
use App\Http\Controllers\API\V1\Admin\PropertyController;
use App\Http\Controllers\API\V1\Admin\PropertyTypeController;
use App\Http\Controllers\API\V1\Admin\RoleController;
use App\Http\Controllers\API\V1\Admin\SellUnitController;
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
Route::post('cities/{city}/media', [CityController::class, 'updateMedia']);
Route::delete('cities/{city}/media', [CityController::class, 'deleteMedia']);
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
Route::patch('properties/{property}/feature', [PropertyController::class, 'toggleFeature']);
Route::post('properties/{property}/media', [PropertyController::class, 'addMedia']);
Route::delete('properties/{property}/media/{media}', [PropertyController::class, 'deleteMediaAction']);
// =========================property=========================

// ========================permission=====================
Route::get('permissions/dropdown', [PermissionController::class, 'dropdown']);
Route::apiResource('permissions', PermissionController::class)->only(['index', 'show', 'destroy']);
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
Route::apiResource('users', UserController::class)->except(['store']);
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
Route::patch('compounds/{compound}/feature', [CompoundController::class, 'toggleFeature']);
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

// ======================Banner Routes==================
Route::apiResource('banners', BannerController::class);
// ======================Banner Routes==================

// ======================Offer Routes==================
Route::apiResource('offers', OfferController::class);
// ======================Offer Routes==================

// ======================Sell-Unit Routes==================
Route::patch('sell-units/{sellUnit}/approve', [SellUnitController::class, 'approve']);
Route::patch('sell-units/{sellUnit}/reject', [SellUnitController::class, 'reject']);
Route::apiResource('sell-units', SellUnitController::class)->only(['index', 'show', 'destroy'])->parameters([
    'sell-units' => 'sellUnit',
]);
// ======================Sell-Unit Routes==================

// ======================Compound Reviews Routes==================
Route::apiResource('compound-reviews', CompoundReviewController::class)->only(['index', 'show', 'destroy'])->parameters([
    'compound-reviews' => 'compoundReview',
]);
// ======================Compound Reviews Routes==================

// ======================Consultant Reviews Routes==================
Route::apiResource('consultant-reviews', ConsultantReviewController::class)->only(['index', 'show', 'destroy'])->parameters([
    'consultant-reviews' => 'consultantReview',
]);
// ======================Consultant Reviews Routes==================

// ======================Chatbot Conversations Routes==================
Route::apiResource('chatbot-conversations', ChatbotConversationController::class)->only(['index', 'show', 'destroy'])->parameters([
    'chatbot-conversations' => 'chatbotConversation',
]);
// ======================Chatbot Conversations Routes==================

// ======================Dashboard Routes==================
Route::get('dashboard', [DashboardController::class, 'show']);
// ======================Dashboard Routes==================

// ======================Newsletter Campaign Routes==================
Route::post('newsletter/campaigns', [NewsletterCampaignController::class, 'send']);
// ======================Newsletter Campaign Routes==================

// ======================Notifications Broadcast Routes==================
Route::post('notifications/broadcast', [NotificationBroadcastController::class, 'broadcast']);
// ======================Notifications Broadcast Routes==================

// ======================Discount Routes==================
Route::apiResource('discounts', DiscountController::class);
// ======================Discount Routes==================
