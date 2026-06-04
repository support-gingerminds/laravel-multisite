<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <div class="row">
                @include('gingerminds-core::components.form.inputs.basic', [
                    'type' => 'text',
                    'id' => 'iso',
                    'label' => __('gingerminds-multisite::translation.form.iso'),
                    'required' => true,
                    'value' => old('iso', isset($language) ? $language->iso : null)
                ])
                @include('gingerminds-core::components.form.inputs.basic', [
                    'type' => 'text',
                    'id' => 'label',
                    'label' => __('gingerminds-core::translation.form.label'),
                    'required' => true,
                    'value' => old('label', isset($language) ? $language->label : null)
                ])
            </div>
        </div>
    </div>
</div>
