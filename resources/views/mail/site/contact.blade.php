@extends('layouts.mail')

@section('content')
    <h1>{{ __('mail.hello') }} {{ config('app.name') }}</h1>
    <p><b>{{ __('site.contact.name') }}</b> : {{ $contact['name'] }}</p>
    <p><b>{{ __('site.contact.email') }}</b> : {{ $contact['email'] }}</p>
    <p><b>{{ __('site.contact.phone') }}</b> : {{ $contact['phone'] }}</p>
    <p><b>{{ __('site.contact.subject') }}</b> : {{ $contact['subject'] }}</p>
    <p><b>{{ __('site.contact.message') }}</b> : {{ $contact['message'] }}</p>

    <p>{{ __('mail.regards') }}, <br> {{ config('app.name') }}</p>
@endsection
