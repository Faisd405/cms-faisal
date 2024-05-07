<?php

use App\Http\Controllers\ContentTypeController;
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
        Route::get('/{contentType}/edit', [ContentTypeController::class, 'edit'])->name('edit');
        Route::put('/{contentType}', [ContentTypeController::class, 'update'])->name('update');
        Route::delete('/{contentType}', [ContentTypeController::class, 'destroy'])->name('destroy');
    });
});
