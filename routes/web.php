<?php

declare(strict_types=1);

use Gingerminds\LaravelMultisite\Http\Controllers\Language\LanguageController;
use Gingerminds\LaravelMultisite\Http\Controllers\Site\SiteController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')
    ->name('gingerminds-multisite.')
    ->group(function () {
        Route::resource('sites', SiteController::class);
        Route::resource('languages', LanguageController::class);
    });
