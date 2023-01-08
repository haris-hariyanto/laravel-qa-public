<x-admin.layouts.app :breadcrumb="$breadcrumb">
    <x-slot:pageTitle>{{ __('Edit User') }}</x-slot>
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
            
            <form action="{{ route('admin.users.update', ['user' => $user]) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card">
                    <div class="card-body">

                        <x-admin.forms.input-text name="username" :label="__('Username')" :value="old('username') ?? $user->username" />
                        <x-admin.forms.input-text name="email" :label="__('Email')" :value="old('email') ?? $user->email" />
                        
                        @can('auth-check', $userAuth->authorize('admin-users-group'))
                            <x-admin.forms.select name="group_id" :label="__('Group')" :options="$groups" :selected="$user->group_id" />
                        @endcan

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-admin.layouts.app>