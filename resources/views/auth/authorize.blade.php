@extends('auth.layouts.default')

@section('title', 'Codethereal | ' . __('auth.authorize_device'))

@section('content')
    <div class="inner-box">
        <p>{{ __('auth.authorize_device_text') }}</p>
        <p>{{ __('auth.didnt_get_email') }}</p>
        {{ Form::open(['route' => 'authorize.resend']) }}
        <div class="auth-btn">
            {{ Form::submit(__('auth.resend_email')) }}
        </div>
        {{ Form::close() }}
    </div>
@endsection
