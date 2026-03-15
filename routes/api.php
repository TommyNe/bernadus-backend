<?php

use App\Http\Controllers\Api\CategoryContentController;
use App\Http\Controllers\Api\NavigationItemController;
use Illuminate\Support\Facades\Route;

Route::get('navigation', [NavigationItemController::class, 'index'])->name('api.navigation.index');
Route::get('navigation/{navigationItem}', [NavigationItemController::class, 'show'])->name('api.navigation.show');
Route::get('category-content', [CategoryContentController::class, 'index'])->name('api.category-content.index');
Route::get('category-content/{category}', [CategoryContentController::class, 'show'])
    ->whereIn('category', ['start', 'about', 'events', 'service-materials', 'gallery-honors', 'participation', 'contact'])
    ->name('api.category-content.show');
