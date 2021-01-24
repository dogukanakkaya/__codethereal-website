@extends('layouts.auth')

@section('title', 'Codethereal | ' . __('passwords.forgot_password'))

@section('content')
    <div class="inner-box">
        {{ Form::open(['route' => 'password.email']) }}
        <div class="f-group">
            {{ Form::label('email', __('users.email')) }}
            {{ Form::email('email', '', ['required' => 'true']) }}
        </div>
        <div class="auth-btn">
            {{ Form::submit(__('passwords.sent_reset_link')) }}
        </div>
        {{ Form::close() }}
    </div>
@endsection
