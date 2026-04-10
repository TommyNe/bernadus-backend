<?php

use App\Http\Controllers\Api\ChronicleController;
use App\Http\Controllers\Api\ChronicleEntryController;
use App\Http\Controllers\Api\CompetitionController;
use App\Http\Controllers\Api\CompetitionResultCategoryController;
use App\Http\Controllers\Api\CompetitionResultController;
use App\Http\Controllers\Api\CompetitionTypeController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\CurrentFlyerController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\ExternalLinkController;
use App\Http\Controllers\Api\GalleryController;
use App\Http\Controllers\Api\JacketListingController;
use App\Http\Controllers\Api\MediumController;
use App\Http\Controllers\Api\PageContentController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\PageSectionController;
use App\Http\Controllers\Api\PersonController;
use App\Http\Controllers\Api\PlaqueAwardRuleController;
use App\Http\Controllers\Api\PlaqueShootingCompetitionController;
use App\Http\Controllers\Api\RoleAssignmentController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\TrophyShootingCompetitionController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VenueController;
use Illuminate\Support\Facades\Route;

Route::get('contact', ContactController::class)->name('api.contact.show');

Route::prefix('pages')->name('api.pages.')->group(function (): void {
    Route::get('/', [PageController::class, 'index'])->name('index');
    Route::get('{slug}/content', [PageContentController::class, 'show'])->name('content');
    Route::get('{value}', [PageController::class, 'show'])->name('show');
});

Route::prefix('page-sections')->name('api.page-sections.')->group(function (): void {
    Route::get('/', [PageSectionController::class, 'index'])->name('index');
    Route::get('{value}', [PageSectionController::class, 'show'])->name('show');
});

Route::prefix('external-links')->name('api.external-links.')->group(function (): void {
    Route::get('/', [ExternalLinkController::class, 'index'])->name('index');
    Route::get('{value}', [ExternalLinkController::class, 'show'])->name('show');
});

Route::prefix('venues')->name('api.venues.')->group(function (): void {
    Route::get('/', [VenueController::class, 'index'])->name('index');
    Route::get('{value}', [VenueController::class, 'show'])->name('show');
});

Route::prefix('events')->name('api.events.')->group(function (): void {
    Route::get('/', [EventController::class, 'index'])->name('index');
    Route::get('{value}', [EventController::class, 'show'])->name('show');
});

Route::prefix('competition-types')->name('api.competition-types.')->group(function (): void {
    Route::get('/', [CompetitionTypeController::class, 'index'])->name('index');
    Route::get('{value}', [CompetitionTypeController::class, 'show'])->name('show');
});

Route::prefix('competitions')->name('api.competitions.')->group(function (): void {
    Route::get('/', [CompetitionController::class, 'index'])->name('index');
    Route::get('plaque-shooting', [PlaqueShootingCompetitionController::class, 'index'])->name('plaque-shooting.index');
    Route::get('plaque-shooting/{year}', [PlaqueShootingCompetitionController::class, 'show'])->name('plaque-shooting.show');
    Route::get('trophy-shooting', [TrophyShootingCompetitionController::class, 'index'])->name('trophy-shooting.index');
    Route::get('trophy-shooting/{year}', [TrophyShootingCompetitionController::class, 'show'])->name('trophy-shooting.show');
    Route::get('{value}', [CompetitionController::class, 'show'])->name('show');
});

Route::prefix('admin/competitions')->name('api.admin.competitions.')->middleware('auth')->group(function (): void {
    Route::post('trophy-shooting', [TrophyShootingCompetitionController::class, 'store'])->name('trophy-shooting.store');
});

Route::prefix('competition-result-categories')->name('api.competition-result-categories.')->group(function (): void {
    Route::get('/', [CompetitionResultCategoryController::class, 'index'])->name('index');
    Route::get('{value}', [CompetitionResultCategoryController::class, 'show'])->name('show');
});

Route::prefix('competition-results')->name('api.competition-results.')->group(function (): void {
    Route::get('/', [CompetitionResultController::class, 'index'])->name('index');
    Route::get('{value}', [CompetitionResultController::class, 'show'])->name('show');
});

Route::prefix('plaque-award-rules')->name('api.plaque-award-rules.')->group(function (): void {
    Route::get('/', [PlaqueAwardRuleController::class, 'index'])->name('index');
    Route::get('{value}', [PlaqueAwardRuleController::class, 'show'])->name('show');
});

Route::prefix('chronicles')->name('api.chronicles.')->group(function (): void {
    Route::get('/', [ChronicleController::class, 'index'])->name('index');
    Route::get('{value}', [ChronicleController::class, 'show'])->name('show');
});

Route::prefix('chronicle-entries')->name('api.chronicle-entries.')->group(function (): void {
    Route::get('/', [ChronicleEntryController::class, 'index'])->name('index');
    Route::get('{value}', [ChronicleEntryController::class, 'show'])->name('show');
});

Route::prefix('media')->name('api.media.')->group(function (): void {
    Route::get('/', [MediumController::class, 'index'])->name('index');
    Route::get('{value}', [MediumController::class, 'show'])->name('show');
});

Route::prefix('gallery')->name('api.gallery.')->group(function (): void {
    Route::get('/', [GalleryController::class, 'index'])->name('index');
});

Route::prefix('flyer')->name('api.flyer.')->group(function (): void {
    Route::get('current', CurrentFlyerController::class)->name('current');
});

Route::prefix('jackenboerse')->name('api.jackenboerse.')->group(function (): void {
    Route::get('/', [JacketListingController::class, 'index'])->name('index');
    Route::get('{value}', [JacketListingController::class, 'show'])->name('show');
});

Route::prefix('people')->name('api.people.')->group(function (): void {
    Route::get('/', [PersonController::class, 'index'])->name('index');
    Route::get('{value}', [PersonController::class, 'show'])->name('show');
});

Route::prefix('roles')->name('api.roles.')->group(function (): void {
    Route::get('/', [RoleController::class, 'index'])->name('index');
    Route::get('{value}', [RoleController::class, 'show'])->name('show');
});

Route::prefix('role-assignments')->name('api.role-assignments.')->group(function (): void {
    Route::get('/', [RoleAssignmentController::class, 'index'])->name('index');
    Route::get('{value}', [RoleAssignmentController::class, 'show'])->name('show');
});

Route::prefix('users')->name('api.users.')->group(function (): void {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('{value}', [UserController::class, 'show'])->name('show');
});
