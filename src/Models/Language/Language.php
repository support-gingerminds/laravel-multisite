<?php

declare(strict_types=1);

namespace Gingerminds\LaravelMultisite\Models\Language;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use Gingerminds\LaravelCore\Models\ResourceModelInterface;
use Gingerminds\LaravelCore\Models\SortableModelInterface;
use Gingerminds\LaravelMultisite\ApiProvider\Language\LanguageProvider;
use Gingerminds\LaravelMultisite\StateProcessor\Language\LanguageStateProcessor;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Serializer\Attribute\Groups;

#[ApiResource(
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => [Language::GROUP_LIST]],
            provider: LanguageProvider::class
        ),
        new Get(
            normalizationContext: ['groups' => [Language::GROUP_READ]],
            provider: LanguageProvider::class
        ),
        new Post(
            normalizationContext: ['groups' => [Language::GROUP_READ]],
            denormalizationContext: ['groups' => [Language::GROUP_EDIT]],
            deserialize: false,
            provider: LanguageProvider::class,
            processor: LanguageStateProcessor::class
        ),
        new Delete(),
        new Patch(
            normalizationContext: ['groups' => [Language::GROUP_READ]],
            denormalizationContext: ['groups' => [Language::GROUP_EDIT]],
            deserialize: false,
            provider: LanguageProvider::class,
            processor: LanguageStateProcessor::class
        ),
    ],
)]
#[ApiProperty(
    identifier: true,
    property: 'id',
    serialize: new Groups([
        Language::GROUP_LIST,
        Language::GROUP_READ,
    ])
)]
class Language extends Model implements ResourceModelInterface, SortableModelInterface
{
    public const string GROUP_LIST = 'languages:list';
    public const string GROUP_READ = 'languages:read';
    public const string GROUP_EDIT = 'languages:edit';

    protected $fillable = [
        'iso',
        'label',
    ];
}
