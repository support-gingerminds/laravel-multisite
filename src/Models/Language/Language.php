<?php

declare(strict_types=1);

namespace Gingerminds\LaravelMultisite\Models\Language;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use Gingerminds\LaravelCore\Models\ResourceModelInterface;
use Gingerminds\LaravelCore\Models\SortableModelInterface;
use Gingerminds\LaravelMultisite\Models\Site\Site;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Serializer\Attribute\Groups;

/**
 * @property string $iso
**/
#[ApiResource(
    operations: [],
)]
#[ApiProperty(
    identifier: true,
    property: 'id',
    serialize: new Groups([
        Language::GROUP_LIST,
        Language::GROUP_READ,
        Site::GROUP_LIST,
        Site::GROUP_READ,
    ])
)]
#[ApiProperty(
    property: 'iso',
    serialize: new Groups([
        Language::GROUP_LIST,
        Language::GROUP_READ,
        Site::GROUP_LIST,
        Site::GROUP_READ,
    ])
)]
#[ApiProperty(
    property: 'label',
    serialize: new Groups([
        Language::GROUP_LIST,
        Language::GROUP_READ,
        Site::GROUP_LIST,
        Site::GROUP_READ,
    ])
)]
class Language extends Model implements ResourceModelInterface, SortableModelInterface
{
    public const string GROUP_LIST = 'languages:list';
    public const string GROUP_READ = 'languages:read';

    /**
     * @return string[]
     */
    public function getFillable(): array
    {
        return [
            'iso',
            'label',
        ];
    }
}
