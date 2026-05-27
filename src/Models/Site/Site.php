<?php

declare(strict_types=1);

namespace Gingerminds\LaravelMultisite\Models\Site;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use Gingerminds\LaravelCore\Models\ResourceModelInterface;
use Gingerminds\LaravelMultisite\ApiProvider\Site\SiteProvider;
use Gingerminds\LaravelMultisite\StateProcessor\Site\SiteStateProcessor;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Serializer\Attribute\Groups;

#[ApiResource(
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => [Site::GROUP_LIST]],
            provider: SiteProvider::class
        ),
        new Get(
            normalizationContext: ['groups' => [Site::GROUP_READ]],
            provider: SiteProvider::class
        ),
        new Post(
            normalizationContext: ['groups' => [Site::GROUP_READ]],
            denormalizationContext: ['groups' => [Site::GROUP_EDIT]],
            deserialize: false,
            provider: SiteProvider::class,
            processor: SiteStateProcessor::class
        ),
        new Delete(),
        new Patch(
            normalizationContext: ['groups' => [Site::GROUP_READ]],
            denormalizationContext: ['groups' => [Site::GROUP_EDIT]],
            deserialize: false,
            provider: SiteProvider::class,
            processor: SiteStateProcessor::class
        ),
    ],
)]
#[ApiProperty(
    identifier: true,
    property: 'id',
    serialize: new Groups([
        Site::GROUP_LIST,
        Site::GROUP_READ,
    ])
)]
class Site extends Model implements ResourceModelInterface
{
    public const string GROUP_LIST = 'sites:list';
    public const string GROUP_READ = 'sites:read';
    public const string GROUP_EDIT = 'sites:edit';

    protected $fillable = [
        'code',
        'url',
    ];
}
