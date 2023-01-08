<x-main.layouts.app>
    <x-slot:pageTitle>{{ __('Login') }}</x-slot>

    <div class="container">
        <div class="row g-2 justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6">

                @if (session('status'))
                    <div class="alert alert-success mb-2">{{ session('status') }}</div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <h1 class="fs-2">{{ __('Login') }}</h1>

                        <form action="{{ route('login') }}" method="POST">
                            @csrf

                            <x-main.forms.input-text name="email" :label="__('Email') . ' / ' . __('Username')" />
                            <x-main.forms.password name="password" :label="__('Password')" />

                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" name="remember" id="checkboxRemember" class="form-check-input" @checked(old() ? old('remember') : true)>
                                    <label for="checkboxRemember" class="form-check-label">{{ __('Remember me') }}</label>
                                </div>
                            </div>

                            @if (Route::has('password.request'))
                                <div class="mb-3 text-end">
                                    <a href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a>
                                </div>
                            @endif

                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">{{ __('Login') }}</button>
                            </div>
                        </form>

                        @if (config('app.open_register'))
                            <p class="mb-0">
                                {{ __('Don\'t have an account?') }} <a href="{{ route('register') }}">{{ __('Register') }}</a>
                            </p>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-main.layouts.app>