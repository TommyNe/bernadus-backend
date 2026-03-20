<?php

use App\Http\Controllers\Api\CategoryContentController;
use App\Http\Controllers\Api\ClubEventController;
use App\Http\Controllers\Api\NavigationItemController;
use App\Http\Controllers\Api\RoyalCourtController;
use App\Http\Controllers\Api\SubpageItemController;
use App\Http\Controllers\Api\TeamMemberController;
use Illuminate\Support\Facades\Route;

Route::get('navigation', [NavigationItemController::class, 'index'])->name('api.navigation.index');
Route::get('navigation/{navigationItem}', [NavigationItemController::class, 'show'])->name('api.navigation.show');
Route::get('category-content', [CategoryContentController::class, 'index'])->name('api.category-content.index');
Route::get('category-content/{category}', [CategoryContentController::class, 'show'])
    ->whereIn('category', ['general-pages', 'about', 'team', 'events', 'gallery', 'membership', 'contact'])
    ->name('api.category-content.show');
Route::get('events', [ClubEventController::class, 'index'])->name('api.events.index');
Route::get('events/{clubEvent}', [ClubEventController::class, 'show'])->name('api.events.show');
Route::get('team-members', [TeamMemberController::class, 'index'])->name('api.team-members.index');
Route::get('team-members/{teamMember}', [TeamMemberController::class, 'show'])->name('api.team-members.show');
Route::get('royal-courts', [RoyalCourtController::class, 'index'])->name('api.royal-courts.index');
Route::get('royal-courts/{royalCourt}', [RoyalCourtController::class, 'show'])->name('api.royal-courts.show');
Route::get('subpage-items', [SubpageItemController::class, 'index'])->name('api.subpage-items.index');
Route::get('subpage-items/category/{category}', [SubpageItemController::class, 'byCategory'])->name('api.subpage-items.category');
Route::get('subpage-items/{subpageItem}', [SubpageItemController::class, 'show'])->name('api.subpage-items.show');
