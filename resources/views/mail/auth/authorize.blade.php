@extends('mail.auth.layouts.default')

@section('content')
    <div class="info">
        <p>{{ __('auth.authorize_email_greeting') }}.</p>
        <div class="list-type">
            <p><span class="text-bold text-dark">{{ __('auth.ip_address') }}:</span> <span class="text-gray">{{ $authorize->ip_address }}</span></p>
            <p><span class="text-bold text-dark">{{ __('auth.browser') }}:</span> <span class="text-gray">{{ $authorize->browser }} ({{ $authorize->platform }})</span></p>
            <p><span class="text-bold text-dark">{{ __('auth.location') }}:</span> <span class="text-gray">{{ $authorize->location }}</span></p>
        </div>
        <p>{{ __('auth.authorize_approve_text') }}.</p>
    </div>
    <div class="click-button">
        <a href="{{ route('authorize.verify', ['token' => $authorize->token]) }}">{{ __('auth.authorize_device') }}</a>
    </div>
@endsection
