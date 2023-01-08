<x-main.layouts.app :use-recaptcha="true">
    <x-slot:pageTitle>{{ __('Register') }}</x-slot>

    <div class="container">
        <div class="row g-2 justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6">

                @if (session('recaptchaInvalid'))
                    <div class="alert alert-danger mb-2">{{ session('recaptchaInvalid') }}</div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <h1 class="fs-2">{{ __('Register') }}</h1>

                        <form action="{{ route('register') }}" method="POST">
                            @csrf

                            <x-main.forms.input-text name="username" :label="__('Username')" />
                            <x-main.forms.input-text name="email" :label="__('Email')" />
                            <x-main.forms.password name="password" :label="__('Password')" />

                            <div class="mb-3">
                                <x-main.forms.recaptcha size="normal" />
                            </div>

                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">{{ __('Register') }}</button>
                            </div>
                        </form>

                        <p class="mb-0">{{ __('Already registered?') }} <a href="{{ route('login') }}">{{ __('Login') }}</a></p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-main.layouts.app>