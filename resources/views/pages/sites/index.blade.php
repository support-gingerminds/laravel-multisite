@extends('gingerminds-core::layouts.crud.list')

@php
    $filters = request()->get('filters', []);
    $indexRoute = 'gingerminds-multisite.sites.index';
@endphp

@section('title')
    @lang('gingerminds-multisite::translation.sites.manage')
@endsection

@section('breadcrumb')
    <x-gingerminds-core::navigation.breadcrumb
        :title="__('gingerminds-core::translation.title_list', ['model' => __('gingerminds-multisite::translation.sites.name_p')])"
        :items="[
            ['label' => __('gingerminds-multisite::translation.sites.name_p'), 'url' => route('gingerminds-multisite.sites.index')],
            ['label' => __('gingerminds-multisite::translation.sites.manage'), 'active' => true],
        ]"
    />
@endsection

@section('actions')
    <a href="{{ route('gingerminds-multisite.sites.create') }}" class="btn btn-sm btn-success">
        <i class="bi bi-plus-lg me-1"></i> @lang('gingerminds-core::translation.title_m_create', ['model' => __('gingerminds-multisite::translation.sites.name_s')])
    </a>
@endsection

@php
    $columns = [
        ['name' => '#', 'sortable' => false],
        ['name' => __('gingerminds-core::translation.form.code'), 'sortable' => true, 'property' => 'code'],
        ['name' => __('gingerminds-multisite::translation.form.default_language'), 'sortable' => false],
        ['name' => __('gingerminds-core::translation.actions'), 'sortable' => false],
    ];
    $sortBy = request()->query('sortBy');
    $sortOrder = request()->query('sort');
@endphp

@section('table_list')
    @include('gingerminds-multisite::pages.sites.partials.list')
@endsection

@push('modals')
    <x-gingerminds-core::modal.modal-delete :model="__('gingerminds-multisite::translation.sites.name_s')" routing="gingerminds-multisite.sites"/>
@endpush
