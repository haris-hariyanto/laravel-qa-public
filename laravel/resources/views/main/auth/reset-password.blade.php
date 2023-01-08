<x-main.layouts.app>
    <x-slot:pageTitle>{{ __('Reset Password') }}</x-slot>

    <div class="container">
        <div class="row g-2 justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6">

                <div class="card">
                    <div class="card-body">
                        <h1 class="fs-2">{{ __('Reset Password') }}</h1>

                        <form action="{{ route('password.update') }}" method="POST">
                            @csrf

                            <!-- Password Reset Token -->
                            <input type="hidden" name="token" value="{{ $request->route('token') }}">

                            <x-main.forms.input-text name="email" :label="__('Email')" :value="old('email', $request->email)" disabled="disabled" />
                            <x-main.forms.password name="password" :label="__('Password')" />
                            <x-main.forms.password name="password_confirmation" :label="__('Confirm Password')" />

                            <div>
                                <button type="submit" class="btn btn-primary">{{ __('Reset Password') }}</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-main.layouts.app>