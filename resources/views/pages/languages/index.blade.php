@extends('gingerminds-core::layouts.crud.list')

@php
    $filters = request()->get('filters', []);
    $indexRoute = 'gingerminds-multisite.languages.index';
@endphp

@section('title')
    @lang('gingerminds-multisite::translation.languages.manage')
@endsection

@section('breadcrumb')
    <x-gingerminds-core::navigation.breadcrumb
        :title="__('gingerminds-core::translation.title_list', ['model' => __('gingerminds-multisite::translation.languages.name_p')])"
        :items="[
            ['label' => __('gingerminds-multisite::translation.languages.name_p'), 'url' => route('gingerminds-multisite.languages.index')],
            ['label' => __('gingerminds-multisite::translation.languages.manage'), 'active' => true],
        ]"
    />
@endsection

@section('actions')
    <a href="{{ route('gingerminds-multisite.languages.create') }}" class="btn btn-sm btn-success">
        <i class="bi bi-plus-lg me-1"></i> @lang('gingerminds-core::translation.title_m_create', ['model' => __('gingerminds-multisite::translation.languages.name_s')])
    </a>
@endsection

@php
    $columns = [
        ['name' => '#', 'sortable' => false],
        ['name' => __('gingerminds-multisite::translation.form.iso'), 'sortable' => true, 'property' => 'iso'],
        ['name' => __('gingerminds-core::translation.form.label'), 'sortable' => true, 'property' => 'label'],
        ['name' => __('gingerminds-core::translation.actions'), 'sortable' => false],
    ];
    $sortBy = request()->query('sortBy');
    $sortOrder = request()->query('sort');
@endphp

@section('table_list')
    @include('gingerminds-multisite::pages.languages.partials.list')
@endsection

@push('modals')
    <x-gingerminds-core::modal.modal-delete :model="__('gingerminds-multisite::translation.languages.name_s')" routing="gingerminds-multisite.languages"/>
@endpush
