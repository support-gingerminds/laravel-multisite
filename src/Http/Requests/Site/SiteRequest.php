<?php

declare(strict_types=1);

namespace Gingerminds\LaravelMultisite\Http\Requests\Site;

use Gingerminds\LaravelCore\Http\Requests\FormRequestInterface;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class SiteRequest extends FormRequest implements FormRequestInterface
{
    protected function prepareForValidation(): void
    {
        /** @var array<int, int|string|null> $rawLanguages */
        $rawLanguages = $this->input('languages', []);

        $selectedLanguages = collect($rawLanguages)
            ->filter(fn ($id): bool => $id !== null && $id !== '')
            ->map(fn ($id): int => (int) $id)
            ->values();

        $defaultLanguage = $this->input('default_language');

        $defaultLanguageId = ($defaultLanguage === null || $defaultLanguage === '')
            ? null
            : (int) $defaultLanguage;

        $languages = $selectedLanguages
            ->mapWithKeys(fn (int $id): array => [
                $id => [
                    'id'         => $id,
                    'is_default' => $defaultLanguageId !== null
                        && $defaultLanguageId === $id,
                ],
            ])
            ->all();

        $this->merge([
            'default_language' => $defaultLanguageId,
            'languages'        => $languages,
        ]);
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            /** @var array<int, array{id:int|string}> $languages */
            $languages = $this->input('languages', []);

            $selected = collect($languages)
                ->pluck('id')
                ->map(fn ($id): int => (int) $id)
                ->all();

            $default = $this->input('default_language');

            if (
                $default    !== null
                && $default !== ''
                && !in_array((int) $default, $selected, true)
            ) {
                $validator->errors()->add(
                    'default_language',
                    __('validation.in')
                );
            }
        });
    }

    /** @return  string[] */
    public function rules(): array
    {
        return [
            'code'             => 'required|string|max:255',
            'url'              => 'required|url',
            'default_language' => 'nullable|integer|exists:languages,id',
            'languages'        => 'nullable|array',
        ];
    }
}
