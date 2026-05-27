@extends('gingerminds-core::layouts.crud.form')

@section('title')
    @lang('gingerminds-core::translation.title_m_create', ['model' => __('gingerminds-multisite::translation.sites.name_s')])
@endsection

@section('breadcrumb')
    <x-gingerminds-core::navigation.breadcrumb
        :title="__('gingerminds-core::translation.title_m_create', ['model' => __('gingerminds-multisite::translation.sites.name_s')])"
        :items="[
            ['label' => __('gingerminds-multisite::translation.sites.name_p'), 'url' => route('gingerminds-multisite.sites.index')],
            ['label' => __('gingerminds-core::translation.title_m_create', ['model' => __('gingerminds-multisite::translation.sites.name_s')]), 'active' => true],
        ]"
    />
@endsection

@php
    $action = route('gingerminds-multisite.sites.store');
    $indexRoute = route('gingerminds-multisite.sites.index');
    $method = 'POST';
    $id = 'create-sites-form';
    $title = __('gingerminds-core::translation.title_m_create', ['model' => __('gingerminds-multisite::translation.sites.name_s')]);
@endphp

@section('fields')
    @include('gingerminds-multisite::pages.sites.partials.fields')
@endsection
