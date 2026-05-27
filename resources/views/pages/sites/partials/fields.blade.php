<div class="col-lg-8">
    <div class="card">
        <div class="card-body">
            <div class="row">
                @include('gingerminds-core::components.form.inputs.basic', [
                    'type' => 'text',
                    'id' => 'code',
                    'label' => __('gingerminds-core::translation.form.code'),
                    'required' => true,
                    'value' => old('code', isset($site) ? $site->code : null)
                ])
                @include('gingerminds-core::components.form.inputs.basic', [
                    'type' => 'url',
                    'id' => 'url',
                    'label' => __('gingerminds-multisite::translation.form.url'),
                    'required' => true,
                    'value' => old('url', isset($site) ? $site->url : null)
                ])
            </div>
        </div>
    </div>
</div>
<div class="col-lg-4">
    <div class="card">
        <div class="card-body">
            @php
                $oldLanguages = old('languages');
                $siteLanguages = isset($site) ? $site->languages : collect();
                $selectedLanguageIds = collect();
                $defaultLanguageId = null;

                if (is_array($oldLanguages)) {
                    $selectedLanguageIds = collect(array_keys($oldLanguages))
                        ->filter(fn ($id) => !empty($oldLanguages[$id]['id']))
                        ->map(fn ($id) => (int) $id);

                    $defaultLanguage = collect($oldLanguages)
                        ->first(fn ($language) => (int) ($language['is_default'] ?? 0) === 1);
                    $defaultLanguageId = $defaultLanguage['id'] ?? null;
                } else {
                    $selectedLanguageIds = $siteLanguages->pluck('id');
                    $defaultLanguageId = $siteLanguages->firstWhere('pivot.is_default', true)?->id;
                }
            @endphp

            <div class="row mb-3">
                <x-gingerminds-core::form.inputs.select
                        id="languages[]"
                        :label="__('gingerminds-multisite::translation.languages.name_p')"
                        :required="false"
                        size="xl"
                        :multiple="true"
                >
                    @foreach($languages as $language)
                        <option
                                value="{{ $language->id }}"
                                {{ $selectedLanguageIds->contains($language->id) ? 'selected' : '' }}
                        >
                            {{ $language->label }} ({{ strtoupper($language->iso) }})
                        </option>
                    @endforeach
                </x-gingerminds-core::form.inputs.select>
            </div>
            <div class="row">
                <x-gingerminds-core::form.inputs.select
                        id="default_language"
                        :label="__('gingerminds-multisite::translation.form.default_language')"
                        :required="false"
                        size="xl"
                >
                    @foreach($languages as $language)
                        <option
                                value="{{ $language->id }}"
                                {{ (int) $defaultLanguageId === (int) $language->id ? 'selected' : '' }}
                        >
                            {{ $language->label }} ({{ strtoupper($language->iso) }})
                        </option>
                    @endforeach
                </x-gingerminds-core::form.inputs.select>
            </div>
        </div>
    </div>
</div>
