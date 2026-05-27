<?php

namespace Gingerminds\LaravelMultisite\Repositories\Site;

use Gingerminds\LaravelCore\Http\Requests\FormRequestInterface;
use Gingerminds\LaravelCore\Models\ResourceModelInterface;
use Gingerminds\LaravelCore\Repositories\AbstractRepository;
use Gingerminds\LaravelCore\Repositories\RepositoryInterface;
use Gingerminds\LaravelMultisite\Models\Site\Site;
use InvalidArgumentException;

/**
 * @extends AbstractRepository<Site>
 * @implements RepositoryInterface<Site>
 */
class SiteRepository extends AbstractRepository implements RepositoryInterface
{
    public function getModelClass(): string
    {
        return Site::class;
    }

    public function update(
        ?FormRequestInterface $request,
        ResourceModelInterface $resourceModel
    ): ResourceModelInterface {
        if (!$resourceModel instanceof Site) {
            throw new InvalidArgumentException('ResourceModelInterface must be an instance of ' . Site::class);
        }

        if (!$request instanceof FormRequestInterface) {
            return $resourceModel;
        }

        $resourceModel->fill($request->all());
        $resourceModel->save();

        return $resourceModel;
    }
}
