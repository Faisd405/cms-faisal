<?php

use App\Http\Controllers\PublicApi\AuthController;
use App\Http\Controllers\PublicApi\PageController;
use App\Http\Controllers\PublicApi\Collection\SectionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public API
Route::get('/pages', [PageController::class, 'index'])->name('pages.index');
Route::get('/pages/{slug}', [PageController::class, 'show'])->name('pages.show');

Route::get('/collection/sections', [SectionController::class, 'index'])->name('sections.index');
Route::get('/collection/sections/{slug}', [SectionController::class, 'show'])->name('sections.show');
Route::get('/collection/sections/{slug}/posts', [SectionController::class, 'posts'])->name('sections.posts');
Route::get('/collection/sections/{slug}/posts/{postSlug}', [SectionController::class, 'postShow'])->name('sections.posts.show');
