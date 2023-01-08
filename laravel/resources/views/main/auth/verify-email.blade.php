<x-main.layouts.app>
    <x-slot:pageTitle>{{ __('Verify Email') }}</x-slot>

    <div class="container">
        <div class="row g-2 justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6">

                @if (session('status') == 'verification-link-sent')
                    <div class="alert alert-success mb-2">{{ __('A new verification link has been sent to the email address you provided during registration.') }}</div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <p>{{ __('Please verify your email address before proceeding. Click the verification link in the email we have sent.') }}</p>

                        <p>{{ __('If you didn\'t receive the email, click the button below to resend it.') }}</p>

                        <div class="d-flex justify-content-between">
                            <div>
                                <form action="{{ route('verification.send') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-primary">{{ __('Resend Verification Email') }}</button>
                                    </div>
                                </form>
                            </div>

                            <div>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
        
                                    <div>
                                        <button type="submit" class="btn btn-link">{{ __('Logout') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-main.layouts.app>