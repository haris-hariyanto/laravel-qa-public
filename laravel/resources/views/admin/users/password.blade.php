<x-admin.layouts.app :breadcrumb="$breadcrumb">
    <x-slot:pageTitle>{{ __('Edit Password') }}</x-slot>
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

            <form action="{{ route('admin.users.password.update', ['user' => $user]) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card">
                    <div class="card-body">

                        <x-admin.forms.input-text name="username" :label="__('Username')" :value="$user->username" readonly="readonly" />
                        <x-admin.forms.input-text name="email" :label="__('Email')" :value="$user->email" readonly="readonly" />
                        <x-admin.forms.password name="password" :label="__('New password')" />
                        <x-admin.forms.password name="password_confirmation" :label="__('Confirm new password')" />

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</x-admin.layouts.app>