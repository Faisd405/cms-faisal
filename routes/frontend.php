<?php

use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\Frontend\SitemapController;
use Illuminate\Support\Facades\Route;

// Frontend Routes (Public Website)
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