<div class="col-lg-12">
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
