<x-admin.layouts.app :breadcrumb="$breadcrumb">
    <x-slot:pageTitle>{{ __('Import Keywords') }}</x-slot>
    <div class="row">
        <div class="col-12 col-lg-6">

            @if (session('success'))
                <x-admin.components.alert>
                    {{ session('success') }}
                </x-admin.components.alert>
            @endif
        
            @if (session('error'))
                <x-admin.components.alert type="danger">
                    {{ session('error') }}
                </x-admin.components.alert>
            @endif

            <form action="{{ route('admin.keywords.import') }}" method="POST">
                @csrf
                
                <div class="card">
                    <div class="card-body">

                        <x-admin.forms.textarea :label="__('Keywords')" name="keywords">{{ old('keywords') }}</x-admin.forms.textarea>

                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary" type="submit">{{ __('Save') }}</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</x-admin.layouts.app>