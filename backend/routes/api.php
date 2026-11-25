<?php

use App\Http\Controllers\Api\AddonController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\GalleryController;
use App\Http\Controllers\Api\HeroController;
use App\Http\Controllers\Api\MotorController;
use App\Http\Controllers\Api\PageSeoController;
use App\Http\Controllers\Api\PricingPackageController;
use App\Http\Controllers\Api\ReviewSettingController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\TestimonialController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/*
 |--------------------------------------------------------------------------
 | Public API Routes (Consumed by Astro Frontend)
 |--------------------------------------------------------------------------
 */
Route::get('/motors', [MotorController::class, 'index']);
Route::get('/motors/{slug}', [MotorController::class, 'show']);
Route::get('/motors/{id}/availability', [MotorController::class, 'checkAvailability']);

Route::post('/bookings', [BookingController::class, 'store']);
Route::get('/bookings/{id}', [BookingController::class, 'show']);

Route::get('/addons', [AddonController::class, 'index']);

Route::get('/faqs', [FaqController::class, 'index']);
Route::get('/gallery', [GalleryController::class, 'index']);
Route::get('/hero', [HeroController::class, 'show']);
Route::get('/pricing-packages', [PricingPackageController::class, 'index']);
Route::get('/seo/{pageKey}', [PageSeoController::class, 'show']);
Route::get('/review-settings', [ReviewSettingController::class, 'show']);
Route::get('/settings', [SettingController::class, 'index']);
Route::get('/testimonials', [TestimonialController::class, 'index']);
