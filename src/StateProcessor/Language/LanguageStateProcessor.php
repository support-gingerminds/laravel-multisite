<?php

declare(strict_types=1);

namespace Gingerminds\LaravelMultisite\StateProcessor\Language;

use ApiPlatform\State\ProcessorInterface;
use Gingerminds\LaravelCore\StateProcessor\BaseStateProcessor;
use Gingerminds\LaravelMultisite\Http\Requests\Language\LanguageRequest;
use Gingerminds\LaravelMultisite\Models\Language\Language;
use Gingerminds\LaravelMultisite\Repositories\Language\LanguageRepository;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;

/**
 * @implements ProcessorInterface<Language, Language>
 */
class LanguageStateProcessor extends BaseStateProcessor implements ProcessorInterface
{
    public function __construct(
        LanguageRepository $repository,
        ValidationFactory $validationFactory
    ) {
        $this->repository    = $repository;
        $this->formRequest   = new LanguageRequest();
        $this->resourceModel = new Language();

        parent::__construct($validationFactory);
    }
}
