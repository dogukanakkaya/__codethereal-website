@extends('auth.layouts.default')

@section('title', 'Codethereal | ' . __('auth.verify_email'))

@section('content')
    <div class="inner-box">
        <p>{{ __('auth.verify_email_text') }} </p>
        {{ Form::open(['route' => 'verification.resend']) }}
            <div class="auth-btn">
                {{ Form::submit(__('buttons.resend')) }}
            </div>
        {{ Form::close() }}
    </div>
@endsection
