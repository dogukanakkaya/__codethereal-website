@extends('auth.layouts.default')

@section('title', 'Codethereal | ' . __('passwords.reset_password'))

@section('content')
    <div class="inner-box">
        {{ Form::open(['route' => 'password.update']) }}
        <div class="f-group">
            {{ Form::label('email', __('users.email')) }}
            {{ Form::email('email', '', ['required' => 'true']) }}
        </div>
        <div class="f-group">
            {{ Form::label('password', __('users.password')) }}
            {{ Form::password('password', ['required' => 'true']) }}
        </div>
        <div class="f-group">
            {{ Form::label('password_confirmation', __('passwords.password_confirmation')) }}
            {{ Form::password('password_confirmation', ['required' => 'true']) }}
        </div>
        {{ Form::hidden('token', $token) }}
        <div class="auth-btn">
            {{ Form::submit(__('passwords.reset_password')) }}
        </div>
        {{ Form::close() }}
    </div>
@endsection
