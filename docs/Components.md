# Blade Components

Views are registered under the `gingerminds-multisite` namespace: `<x-gingerminds-multisite::...>`.

## `form.inputs.translations`

A tabbed UI (one tab per language) for editing a model's translated fields, meant to be paired with [`TranslatableModelTrait`](./Traits.md#translatablemodeltrait).

```blade
<x-gingerminds-multisite::form.inputs.translations
    :languages="$languages"
    :translations="isset($product) ? $product->translations->keyBy('language_id') : []"
    fields-view="pages.products.partials.translation-field"
    :default-language="$defaultLanguage"
/>
```

### Props

| Prop | Default | Description |
|---|---|---|
| `languages` | *required* | The list of `Language` models to render a tab for. |
| `translations` | `[]` | Existing translations, keyed by `language_id` (e.g. `$product->translations->keyBy('language_id')`). |
| `fieldsView` | `null` | The Blade view included inside each tab, responsible for rendering the actual fields for one language. |
| `defaultLanguage` | `null` | Used to mark the default language's fields as `required` inside `fieldsView` (see below). |

### Writing the `fieldsView`

The included view receives three variables per tab:

- `$language` — the `Language` for this tab.
- `$translation` — the existing translation row for this language, or `null`.
- `$required` — `true` only for the tab matching `defaultLanguage`.

```blade
{{-- resources/views/pages/products/partials/translation-field.blade.php --}}
<x-gingerminds-core::form.inputs.basic
    type="text"
    id="translations_{{ $language->id }}_name"
    name="translations[{{ $language->id }}][name]"
    :label="__('gingerminds-core::translation.form.name')"
    :value="old('translations.' . $language->id . '.name', $translation?->name)"
    :required="$required"
/>
```

Submitted this way, the field data arrives as `translations[{language_id}][name]`, ready to hand straight to `$model->syncTranslations($request->input('translations', []))`.

## Admin screens

The package also ships full CRUD admin screens for sites and languages (`{admin_prefix}/sites`, `{admin_prefix}/languages`) — internal, controller-rendered pages, not components meant to be embedded elsewhere.
