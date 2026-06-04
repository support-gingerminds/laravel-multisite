<?php

declare(strict_types=1);

namespace Gingerminds\LaravelMultisite\ApiProvider\Language;

use ApiPlatform\State\ProviderInterface;
use Gingerminds\LaravelCore\ApiProvider\AbstractApiProvider;
use Gingerminds\LaravelCore\ApiProvider\ApiProviderInterface;
use Gingerminds\LaravelMultisite\Models\Language\Language;
use Gingerminds\LaravelMultisite\Repositories\Language\LanguageRepository;

/**
 * @implements ProviderInterface<Language>
 */
class LanguageProvider extends AbstractApiProvider implements ProviderInterface, ApiProviderInterface
{
    public function __construct(LanguageRepository $repository)
    {
        parent::__construct($repository);
    }
}
