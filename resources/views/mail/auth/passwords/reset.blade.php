@extends('layouts.mail')

@section('content')
    <h1>{{ __('mail.hello') }} {{ $name }}</h1>
    <p>{{ __('mail.auth.password_reset_email_reason') }}</p>
    <div class="text-center w-100">
        <a href="{{ route('password.reset', ['token' => $token]) }}" class="click-button">{{ __('mail.auth.reset_password') }}</a>
    </div>
    <p>{{ __('mail.link_expires_in', ['time' => '60 minute']) }}</p>
    <p>{{ __('mail.no_action_if_not_requested') }}</p>
    <p>{{ __('mail.regards') }}, <br> {{ config('app.name') }}</p>
    <div class="line"></div>
    <p>{{ __('mail.cant_click_button', ['button' => __('mail.auth.reset_password')]) }}
        <span class="link-text"><a href="{{ route('password.reset', ['token' => $token]) }}">{{ route('password.reset', ['token' => $token]) }}</a></span>
    </p>
@endsection
