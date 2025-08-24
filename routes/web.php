<?php

use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\Frontend\SitemapController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// SEO routes
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');

// Language switching
Route::get('/language/{languageCode}', [FrontendController::class, 'switchLanguage'])->name('language.switch');

// Frontend routes with language middleware
Route::middleware(['set.language'])->group(function () {
    // Homepage
    Route::get('/', [FrontendController::class, 'home'])->name('home');
    
    // Collection routes (blog, news, etc.)
    Route::get('/collection/{sectionSlug}', [FrontendController::class, 'collection'])->name('collection.index');
    Route::get('/collection/{sectionSlug}/{postSlug}', [FrontendController::class, 'post'])->name('collection.show');
    
    // Dynamic pages (must be last to avoid conflicts)
    Route::get('/{slug}', [FrontendController::class, 'page'])->name('page.show');
});

// Admin routes
include_once __DIR__ . '/admin.php';