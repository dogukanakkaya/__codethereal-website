@extends('layouts.auth')

@section('title', 'Codethereal | ' . __('auth.authorize_device'))

@section('content')
    <div class="inner-box">
        <p>{{ __('auth.authorize_device_text') }}</p>
        {{ Form::open(['route' => 'authorize.resend']) }}
        <div class="auth-btn">
            {{ Form::submit(__('buttons.resend')) }}
        </div>
        {{ Form::close() }}
    </div>
@endsection
