<x-main.emails.wrapper>
    <p>{{ __('Please click the link below to verify your email address.') }}</p>
    <p><a href="{{ $url }}">{{ $url }}</a></p>
    <p>{{ __('If you did not create an account, no further action is required.') }}</p>
    <p>{{ __('Thank you') }},<br>{{ config('app.name') }}</p>
</x-main.emails.wrapper>