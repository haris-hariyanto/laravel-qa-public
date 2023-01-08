<x-admin.layouts.app :breadcrumb="$breadcrumb">
    <x-slot:pageTitle>{{ __('Create User') }}</x-slot>
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

            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                @method('POST')

                <div class="card">
                    <div class="card-body">

                        <x-admin.forms.input-text name="username" :label="__('Username')" />
                        <x-admin.forms.input-text name="email" :label="__('Email')" />
                        <x-admin.forms.select name="group_id" :label="__('Group')" :options="$groups" :selected="old('group_id')" />
                        <x-admin.forms.password name="password" :label="__('Password')" />

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</x-admin.layouts.app>