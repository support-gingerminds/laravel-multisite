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
use Gingerminds\LaravelCore\Models\SortableModelInterface;
use Gingerminds\LaravelMultisite\ApiProvider\Site\SiteProvider;
use Gingerminds\LaravelMultisite\Models\Language\Language;
use Gingerminds\LaravelMultisite\StateProcessor\Site\SiteStateProcessor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
            processor: SiteStateProcessor::class,
        ),
        new Delete(),
        new Patch(
            normalizationContext: ['groups' => [Site::GROUP_READ]],
            denormalizationContext: ['groups' => [Site::GROUP_EDIT]],
            deserialize: false,
            provider: SiteProvider::class,
            processor: SiteStateProcessor::class,
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
#[ApiProperty(
    property: 'code',
    serialize: new Groups([
        Site::GROUP_EDIT,
        Site::GROUP_LIST,
        Site::GROUP_READ,
    ])
)]
#[ApiProperty(
    property: 'url',
    serialize: new Groups([
        Site::GROUP_EDIT,
        Site::GROUP_LIST,
        Site::GROUP_READ,
    ])
)]
#[ApiProperty(
    property: 'languages',
    serialize: new Groups([
        Site::GROUP_READ,
    ])
)]
#[ApiProperty(
    property: 'default_language',
    serialize: new Groups([
        Site::GROUP_LIST,
        Site::GROUP_READ,
    ])
)]
class Site extends Model implements ResourceModelInterface, SortableModelInterface
{
    public const string GROUP_LIST = 'sites:list';
    public const string GROUP_READ = 'sites:read';
    public const string GROUP_EDIT = 'sites:edit';

    protected $fillable = [
        'code',
        'url',
    ];

    /**
     * @return BelongsToMany<Language, $this>
     */
    public function languages(): BelongsToMany
    {
        return $this->belongsToMany(Language::class, 'site_language')
            ->withPivot('is_default')
            ->withTimestamps();
    }

    /**
     * @return BelongsToMany<Language, $this>
     */
    public function defaultLanguage(): BelongsToMany
    {
        return $this->belongsToMany(Language::class, 'site_language')
            ->wherePivot('is_default', true);
    }
}
