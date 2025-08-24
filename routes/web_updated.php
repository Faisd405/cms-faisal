<?php

use App\Http\Controllers\Collection\PostController;
use App\Http\Controllers\Collection\SectionController;
use App\Http\Controllers\ComponentController;
use App\Http\Controllers\ContentTypeController;
use App\Http\Controllers\ContentTypeFieldController;
use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\Frontend\SitemapController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PageController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Admin Login redirect (temporary for welcome page)
Route::get('/admin', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Include admin routes  
include_once __DIR__ . '/admin.php';

// Frontend Routes (Public Website) - at the end to avoid conflicts
Route::middleware(['web', 'set.language'])->group(function () {
    // Homepage
    Route::get('/', [FrontendController::class, 'home'])->name('home');
    
    // Language switcher
    Route::get('/lang/{locale}', [FrontendController::class, 'switchLanguage'])->name('language.switch');
    
    // SEO Routes
    Route::get('/sitemap.xml', [SitemapController::class, 'sitemap'])->name('sitemap');
    Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');
    
    // Collection Routes
    Route::get('/collection/{section}', [FrontendController::class, 'collection'])->name('collection.index');
    Route::get('/collection/{section}/{post}', [FrontendController::class, 'post'])->name('collection.show');
    
    // Dynamic Pages (this should be last to avoid conflicts)
    Route::get('/{page}', [FrontendController::class, 'page'])->name('page.show');
});