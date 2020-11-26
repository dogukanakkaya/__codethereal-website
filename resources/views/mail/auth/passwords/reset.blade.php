@extends('mail.auth.layouts.default')

@section('content')
    <div class="info">
        <p>{{ __('passwords.reset_password_mail_greeting', ['name' => $name]) }}</p>
    </div>
    <div class="click-button">
        <a href="{{ route('password.reset', ['token' => $token]) }}">{{ __('passwords.reset_password') }}</a>
    </div>
@endsection
