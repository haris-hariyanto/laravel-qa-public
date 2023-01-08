<x-main.layouts.app>
    <x-slot:pageTitle>{{ __('Confirm Password') }}</x-slot>

    <div class="container">
        <div class="row g-2 justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6">

                <div class="alert alert-info mb-2">
                    {{ __('Please confirm your password before continuing.') }}
                </div>

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('password.confirm') }}" method="POST">
                            @csrf

                            <x-main.forms.password name="password" :label="__('Password')" />

                            <button type="submit" class="btn btn-primary">{{ __('Confirm') }}</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-main.layouts.app>