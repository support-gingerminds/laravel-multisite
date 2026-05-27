<?php

namespace Gingerminds\LaravelMultisite\Repositories\Language;

use Gingerminds\LaravelCore\Http\Requests\FormRequestInterface;
use Gingerminds\LaravelCore\Models\ResourceModelInterface;
use Gingerminds\LaravelCore\Repositories\AbstractRepository;
use Gingerminds\LaravelCore\Repositories\RepositoryInterface;
use Gingerminds\LaravelMultisite\Models\Language\Language;
use InvalidArgumentException;

/**
 * @extends AbstractRepository<Language>
 * @implements RepositoryInterface<Language>
 */
class LanguageRepository extends AbstractRepository implements RepositoryInterface
{
    public function getModelClass(): string
    {
        return Language::class;
    }

    public function update(
        ?FormRequestInterface $request,
        ResourceModelInterface $resourceModel
    ): ResourceModelInterface {
        if (!$resourceModel instanceof Language) {
            throw new InvalidArgumentException('ResourceModelInterface must be an instance of ' . Language::class);
        }

        if (!$request instanceof FormRequestInterface) {
            return $resourceModel;
        }

        $resourceModel->fill($request->all());
        $resourceModel->save();

        return $resourceModel;
    }
}
