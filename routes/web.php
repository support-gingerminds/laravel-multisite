<?php

declare(strict_types=1);

use Gingerminds\LaravelMultisite\Resolver\ResourceResolver;
use Illuminate\Support\Facades\Route;

Route::middleware('web')
    ->prefix(config('gingerminds-core.admin_prefix'))
    ->name('gingerminds-multisite.')
    ->group(function () {
        Route::resource('sites', ResourceResolver::controller('site'));
        Route::resource('languages', ResourceResolver::controller('language'));
    });
