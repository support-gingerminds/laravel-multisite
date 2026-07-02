@foreach($items as $language)
    <tr>
        <td>{{ $language->id }}</td>
        <td>{{ $language->iso }}</td>
        <td>{{ $language->label }}</td>
        <td class="text-end">
            <fieldset class="btn-group">
                <a class="btn btn-sm btn-outline-primary" href="{{ route('gingerminds-multisite.languages.edit', $language) }}">
                    <i class="bi bi-pencil-square"></i>
                </a>
                <button type="button"
                        class="btn btn-outline-danger btn-sm js-remove-item"
                        data-bs-toggle="modal"
                        data-bs-target="#removeModal"
                        data-model="@lang('gingerminds-multisite.translation.languages.name_s')"
                        data-remove-name="{{ $language->iso ?? $language->id }}"
                        data-destroy-url="{{ route('gingerminds-multisite.languages.destroy', $language) }}"
                >
                    <i class="bi bi-trash"></i>
                </button>
            </fieldset>
        </td>
    </tr>
@endforeach
