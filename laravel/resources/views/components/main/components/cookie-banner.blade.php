@push('scripts')
    <link rel="stylesheet" href="{{ asset('assets/main/plugins/cookie-banner/cookie-banner.css') }}">
    <script async src="{{ asset('assets/main/plugins/cookie-banner/cookie-banner.js') }}"></script>
@endpush

<div id="cb-cookie-banner" class="alert alert-dark text-center mb-0" role="alert">
    <div class="d-flex justify-content-center align-items-center">
        <div>{{ __('This website uses cookies to ensure you get the best experience on our website.') }}</div>
        <button class="btn btn-primary btn-sm ms-3" type="button" id="acceptCookies">{{ __('Accept') }}</button>
    </div>
</div>

@push('scriptsBottom')
    <script>
        const acceptCookies = document.getElementById('acceptCookies');
        acceptCookies.addEventListener('click', () => {
            cb_hideCookieBanner();
        });
    </script>
@endpush