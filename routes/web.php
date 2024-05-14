<?php

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
        Route::get('/create', [PageController::class, 'create'])->name('create');
        Route::post('/', [PageController::class, 'store'])->name('store');
        Route::get('/{pageId}/edit', [PageController::class, 'edit'])->name('edit');
        Route::put('/{pageId}', [PageController::class, 'update'])->name('update');
        Route::delete('/{pageId}', [PageController::class, 'destroy'])->name('destroy');
    });
});
