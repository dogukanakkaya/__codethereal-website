@extends('mail.auth.layouts.default')

@section('content')
    <div class="info">
        <p>{{ __('auth.verify_email_mail_greeting', ['name' => $name]) }}</p>
    </div>
    <div class="click-button">
        <a href="{{ $url }}">{{ __('auth.verify_email') }}</a>
    </div>
@endsection
