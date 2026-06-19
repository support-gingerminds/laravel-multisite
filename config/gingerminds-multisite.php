<?php

use Gingerminds\LaravelMultisite\ApiProvider\Language\LanguageProvider;
use Gingerminds\LaravelMultisite\Http\Requests\Site\SiteRequest;
use Gingerminds\LaravelMultisite\Models\Language\Language;
use Gingerminds\LaravelMultisite\Models\Site\Site;
use Gingerminds\LaravelMultisite\Http\Controllers\Language\LanguageController;
use Gingerminds\LaravelMultisite\Http\Controllers\Site\SiteController;
use Gingerminds\LaravelMultisite\Repositories\Language\LanguageRepository;
use Gingerminds\LaravelMultisite\Repositories\Site\SiteRepository;
use Gingerminds\LaravelMultisite\Http\Requests\Language\LanguageRequest;
use Gingerminds\LaravelMultisite\ApiProvider\Site\SiteProvider;
use Gingerminds\LaravelMultisite\StateProcessor\Site\SiteStateProcessor;

return [
    'resources' => [
        'language' => [
            'model' => Language::class,
            'controller' => LanguageController::class,
            'repository' => LanguageRepository::class,
            'request' => LanguageRequest::class,
            'provider' => LanguageProvider::class
        ],

        'site' => [
            'model' => Site::class,
            'controller' => SiteController::class,
            'repository' => SiteRepository::class,
            'request' => SiteRequest::class,
            'provider' => SiteProvider::class,
            'state_processor' => SiteStateProcessor::class
        ],
    ],
];