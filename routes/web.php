<?php

use App\Http\Controllers\OpenApiDocumentationController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Laravel\Fortify\Features;

Route::inertia('/', 'welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::inertia('galerie', 'gallery', [
    'galleryEndpoint' => '/api/gallery',
    'pageTitle' => 'Galerie',
])->name('gallery');

Route::get('openapi.json', [OpenApiDocumentationController::class, 'json'])
    ->withoutMiddleware([
        StartSession::class,
        ShareErrorsFromSession::class,
        VerifyCsrfToken::class,
    ])
    ->name('openapi.json');

Route::get('docs/api', [OpenApiDocumentationController::class, 'index'])
    ->withoutMiddleware([
        StartSession::class,
        ShareErrorsFromSession::class,
        VerifyCsrfToken::class,
    ])
    ->name('docs.api');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
