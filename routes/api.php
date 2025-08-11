<?php

use App\Http\Controllers\PublicApi\AuthController;
use App\Http\Controllers\PublicApi\PageController;
use App\Http\Controllers\PublicApi\Collection\SectionController;
use App\Http\Controllers\PublicApi\LanguageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public API with rate limiting
Route::middleware(['throttle:200,1'])->group(function () {

    // Pages API
    Route::get('/pages', [PageController::class, 'index'])->name('api.pages.index');
    Route::get('/pages/{slug}', [PageController::class, 'show'])->name('api.pages.show');

    // Collections API
    Route::prefix('collection')->name('api.collection.')->group(function () {
        Route::get('/sections', [SectionController::class, 'index'])->name('sections.index');
        Route::get('/sections/{sectionSlug}', [SectionController::class, 'show'])->name('sections.show');
        Route::get('/sections/{sectionSlug}/posts', [SectionController::class, 'posts'])->name('sections.posts');
        Route::get('/sections/{sectionSlug}/posts/{postSlug}', [SectionController::class, 'postShow'])->name('sections.posts.show');
    });

    // Languages API
    Route::get('/languages', [LanguageController::class, 'index'])->name('api.languages.index');

});
