@foreach($items as $site)
    <tr>
        <td>{{ $site->id }}</td>
        <td>{{ $site->code }}</td>
        <td class="text-end">
            <div class="btn-group" role="group">
                <a class="btn btn-sm btn-outline-primary" href="{{ route('gingerminds-multisite.sites.edit', $site) }}">
                    <i class="bi bi-pencil-square"></i>
                </a>
                <button type="button"
                        class="btn btn-outline-danger btn-sm js-remove-item"
                        data-bs-toggle="modal"
                        data-bs-target="#removeModal"
                        data-model="@lang('gingerminds-multisite.translation.sites.name_s')"
                        data-remove-name="{{ $site->code ?? $site->id }}"
                        data-destroy-url="{{ route('gingerminds-multisite.sites.destroy', $site) }}"
                >
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </td>
    </tr>
@endforeach
