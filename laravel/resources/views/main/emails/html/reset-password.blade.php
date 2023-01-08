<x-main.emails.wrapper>
    <p>{{ $username }},</p>
    <p>{{ __('To complete the phase of resetting your account password, you will need to go to the URL below.') }}</p>
    <p><a href="{{ $resetPasswordURL }}">{{ $resetPasswordURL }}</a></p>
    <p>{{ __('This password reset link will expire in 60 minutes.') }}</p>
    <p>{{ __('Thank you') }},<br>{{ config('app.name') }}</p>
</x-main.emails.wrapper>