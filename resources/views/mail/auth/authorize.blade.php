@extends('layouts.mail')

@section('content')
    <h1>{{ __('mail.hello') }}</h1>
    <p>{{ __('mail.auth.authorize_email_reason') }}</p>
    <div class="list-type">
        <p><span class="text-bold text-dark">{{ __('mail.auth.ip_address') }}:</span> <span class="text-gray">{{ $authorize->ip_address }}</span></p>
        <p><span class="text-bold text-dark">{{ __('mail.auth.browser') }}:</span> <span class="text-gray">{{ $authorize->browser }} ({{ $authorize->platform }})</span></p>
        <p><span class="text-bold text-dark">{{ __('mail.auth.location') }}:</span> <span class="text-gray">{{ $authorize->location }}</span></p>
    </div>
    <p>{{ __('mail.auth.authorize_approve_text') }}</p>
    <div class="text-center w-100">
        <a href="{{ route('authorize.verify', ['token' => $authorize->token]) }}" class="click-button">{{ __('mail.auth.authorize_device') }}</a>
    </div>
    <p>{{ __('mail.link_expires_in', ['time' => '60 minute']) }}</p>
    <p>{{ __('mail.no_action_if_not_requested') }}</p>
    <p>{{ __('mail.regards') }}, <br> {{ config('app.name') }}</p>
    <div class="line"></div>
    <p>{{ __('mail.cant_click_button', ['button' => __('mail.auth.authorize_device')]) }}
        <span class="link-text"><a href="{{ route('authorize.verify', ['token' => $authorize->token]) }}">{{ route('authorize.verify', ['token' => $authorize->token]) }}</a></span>
    </p>
@endsection
