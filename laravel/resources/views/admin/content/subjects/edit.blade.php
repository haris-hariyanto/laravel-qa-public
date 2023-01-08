<x-admin.layouts.app :breadcrumb="$breadcrumb">
    <x-slot:pageTitle>{{ __('Edit Subject') }}</x-slot>
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

            <form action="{{ route('admin.subjects.update', ['subject' => $subject]) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card">
                    <div class="card-body">

                        <x-admin.forms.input-text name="name" :label="__('Subject name')" :value="old('name') ?? $subject->name" />
                        <x-admin.forms.input-text name="slug" :label="__('Slug')" :value="old('slug') ?? $subject->slug" />
                        
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</x-admin.layouts.app>