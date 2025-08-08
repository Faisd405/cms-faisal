<?php

use App\Http\Controllers\Collection\PostController;
use App\Http\Controllers\Collection\SectionController;
use App\Http\Controllers\ComponentController;
use App\Http\Controllers\ContentTypeController;
use App\Http\Controllers\ContentTypeFieldController;
use App\Http\Controllers\LanguageController;
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

include_once __DIR__ . '/admin.php';
