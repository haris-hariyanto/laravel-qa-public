<x-main.layouts.app :use-recaptcha="true">
    <x-slot:pageTitle>{{ __('Forgot Password') }}</x-slot>

    <div class="container">
        <div class="row g-2 justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6">

                @if (session('status'))
                    <div class="alert alert-success mb-2" role="alert">{{ session('status') }}</div>
                @endif

                @if (session('recaptchaInvalid'))
                    <div class="alert alert-danger mb-2">{{ session('recaptchaInvalid') }}</div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <h1 class="fs-2">{{ __('Forgot Password') }}</h1>

                        <p>{{ __('Let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}</p>

                        <form action="{{ route('password.email') }}" method="POST">
                            @csrf

                            <x-main.forms.input-text name="email" :label="__('Email')" />

                            <div class="mb-3">
                                <x-main.forms.recaptcha size="normal" />
                            </div>

                            <div>
                                <button type="submit" class="btn btn-primary">{{ __('Send Reset Link') }}</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-main.layouts.app>