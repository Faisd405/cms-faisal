<?php

use App\Http\Controllers\Collection\CategoryController;
use App\Http\Controllers\Collection\PostController;
use App\Http\Controllers\Collection\SectionController;
use App\Http\Controllers\ContentTypeController;
use App\Http\Controllers\ContentTypeFieldController;
use App\Http\Controllers\PageController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::group(['prefix' => 'content-types', 'as' => 'content-types.'], function () {
        Route::get('/', [ContentTypeController::class, 'index'])->name('index');
        Route::get('/create', [ContentTypeController::class, 'create'])->name('create');
        Route::post('/', [ContentTypeController::class, 'store'])->name('store');
        Route::get('/{contentTypeId}/edit', [ContentTypeController::class, 'edit'])->name('edit');
        Route::put('/{contentTypeId}', [ContentTypeController::class, 'update'])->name('update');
        Route::delete('/{contentTypeId}', [ContentTypeController::class, 'destroy'])->name('destroy');

        Route::group(['prefix' => '{contentTypeId}/fields', 'as' => 'fields.'], function () {
            Route::post('/', [ContentTypeFieldController::class, 'store'])->name('store');
            Route::put('/{fieldId}', [ContentTypeFieldController::class, 'update'])->name('update');
            Route::delete('/{fieldId}', [ContentTypeFieldController::class, 'destroy'])->name('destroy');
        });
    });

    Route::group(['prefix' => 'pages', 'as' => 'pages.'], function () {
        Route::match(['post', 'put'], '/{pageId}/content', [PageController::class, 'updateContent'])->name('update-content');

        Route::get('/', [PageController::class, 'index'])->name('index');
        Route::post('/', [PageController::class, 'store'])->name('store');
        Route::get('/{pageId}/edit', [PageController::class, 'edit'])->name('edit');
        Route::put('/{pageId}', [PageController::class, 'update'])->name('update');
        Route::delete('/{pageId}', [PageController::class, 'destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'collection', 'as' => 'collection.'], function () {
        Route::group(['prefix' => 'categories', 'as' => 'categories.'], function () {
            Route::get('/', [CategoryController::class, 'index'])->name('index');
            Route::get('/create', [CategoryController::class, 'create'])->name('create');
            Route::post('/', [CategoryController::class, 'store'])->name('store');
            Route::get('/{categoryId}/edit', [CategoryController::class, 'edit'])->name('edit');
            Route::put('/{categoryId}', [CategoryController::class, 'update'])->name('update');
            Route::delete('/{categoryId}', [CategoryController::class, 'destroy'])->name('destroy');
        });

        Route::group(['prefix' => 'sections', 'as' => 'sections.'], function () {
            Route::group(['prefix' => '{sectionId}/posts', 'as' => 'posts.'], function () {
                Route::match(['post', 'put'], '/{postId}/content', [PostController::class, 'updateContent'])->name('update-content');

                Route::get('/', [PostController::class, 'index'])->name('index');
                Route::get('/create', [PostController::class, 'create'])->name('create');
                Route::post('/', [PostController::class, 'store'])->name('store');
                Route::get('/{postId}/edit', [PostController::class, 'edit'])->name('edit');
                Route::put('/{postId}', [PostController::class, 'update'])->name('update');
                Route::delete('/{postId}', [PostController::class, 'destroy'])->name('destroy');
            });

            Route::get('/', [SectionController::class, 'index'])->name('index');
            Route::get('/create', [SectionController::class, 'create'])->name('create');
            Route::post('/', [SectionController::class, 'store'])->name('store');
            Route::get('/{sectionId}/edit', [SectionController::class, 'edit'])->name('edit');
            Route::put('/{sectionId}', [SectionController::class, 'update'])->name('update');
            Route::delete('/{sectionId}', [SectionController::class, 'destroy'])->name('destroy');
        });
    });
});
