@extends('layouts.mail')

@section('content')
    <h1>{{ __('mail.hello') }} {{ $name }}</h1>
    <p>{{ __('mail.auth.verify_email_reason') }}</p>
    <div class="text-center w-100">
        <a href="{{ $url }}" class="click-button">{{ __('mail.auth.verify') }}</a>
    </div>
    <p>{{ __('mail.link_expires_in', ['time' => '60 minute']) }}</p>
    <p>{{ __('mail.no_action_if_not_requested') }}</p>
    <p>{{ __('mail.regards') }}, <br> {{ config('app.name') }}</p>
    <div class="line"></div>
    <p>{{ __('mail.cant_click_button', ['button' => __('mail.auth.verify')]) }}
        <span class="link-text"><a href="{{ $url }}">{{ $url }}</a></span>
    </p>
@endsection
