@extends('auth.layouts.default')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
@endpush

@section('title', 'Codethereal | ' . __('auth.login'))

@section('content')
<div class="inner-box">
    {{ Form::open(['action' => 'App\Http\Controllers\Auth\LoginController@login']) }}
    <div class="f-group">
        {{ Form::label('email', __('users.email')) }}
        {{ Form::email('email', '', ['required' => 'true']) }}
    </div>
    <div class="f-group">
        {{ Form::label('password', __('users.password')) }}
        {{ Form::password('password', ['required' => 'true']) }}
    </div>
    <div class="f-group">
        {{__('auth.remember_me')}} {{ Form::checkbox('remember', 'on') }}
    </div>
    <a href="{{ route('password.request') }}" class="forgot-password">{{ __('passwords.forgot_password') }}</a>
    <div class="auth-btn">
        {{ Form::submit(__('auth.login')) }}
    </div>
    {{ Form::close() }}
</div>
@endsection
