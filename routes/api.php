<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContentTypeController;
use App\Http\Controllers\ContentTypeFieldController;
use App\Http\Controllers\PageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });


    Route::group(['prefix' => 'content-types', 'as' => 'content-types.'], function () {
        Route::get('/', [ContentTypeController::class, 'index'])->name('index');
        Route::post('/', [ContentTypeController::class, 'store'])->name('store');
        Route::put('/{contentTypeId}', [ContentTypeController::class, 'update'])->name('update');
        Route::delete('/{contentTypeId}', [ContentTypeController::class, 'destroy'])->name('destroy');

        Route::group(['prefix' => '{contentTypeId}/fields', 'as' => 'fields.'], function () {
            Route::post('/', [ContentTypeFieldController::class, 'store'])->name('store');
            Route::put('/{fieldId}', [ContentTypeFieldController::class, 'update'])->name('update');
            Route::delete('/{fieldId}', [ContentTypeFieldController::class, 'destroy'])->name('destroy');
        });
    });

    Route::group(['prefix' => 'pages', 'as' => 'pages.'], function () {
        Route::get('/', [PageController::class, 'index'])->name('index');
        Route::post('/', [PageController::class, 'store'])->name('store');
        Route::put('/{pageId}', [PageController::class, 'update'])->name('update');
        Route::delete('/{pageId}', [PageController::class, 'destroy'])->name('destroy');
    });
});
