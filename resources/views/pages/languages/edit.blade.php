@extends('gingerminds-core::layouts.crud.form')

@section('title')
    @lang('gingerminds-core::translation.title_m_edit', ['model' => __('gingerminds-multisite::translation.languages.name_s')])
@endsection

@section('breadcrumb')
    <x-gingerminds-core::navigation.breadcrumb
        :title="__('gingerminds-core::translation.title_m_edit', ['model' => __('gingerminds-multisite::translation.languages.name_s')])"
        :items="[
            ['label' => __('gingerminds-multisite::translation.languages.name_p'), 'url' => route('gingerminds-multisite.languages.index')],
            ['label' => __('gingerminds-core::translation.title_m_edit', ['model' => __('gingerminds-multisite::translation.languages.name_s')]), 'active' => true],
        ]"
    />
@endsection

@php
    $action = route('gingerminds-multisite.languages.update', $language);
    $indexRoute = route('gingerminds-multisite.languages.index');
    $method = 'PATCH';
    $id = 'edit-languages-form';
    $title = __('gingerminds-core::translation.title_m_edit', ['model' => __('gingerminds-multisite::translation.languages.name_s')]);
@endphp

@section('fields')
    @include('gingerminds-multisite::pages.languages.partials.fields')
@endsection
