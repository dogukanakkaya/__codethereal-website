<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Codethereal')</title>

    <link rel="stylesheet" href="{{ asset('css/auth/base.css') }}">
    @stack('styles')
</head>
<body>
<div class="contain" style="background-image: url('{{ asset('img/building-background.jpg') }}')">
    <div class="box">
        <div class="logo"><img src="{{ asset('img/Logo.png') }}" alt=""></div>
        @if($errors->any())
            <div class="alert error">
                <p>{{$errors->first()}}</p>
            </div>
        @elseif(session()->has('status') && !request()->is('login'))
            @php
                // If session has status but active route is not login, redirect to login with status
                session()->flash('status', session('status'));
            @endphp
            <script>window.location.href = '{{ route('login') }}'</script>
        @elseif(session()->has('resent') && !request()->is('login'))
            @php
                // If session has resent but active route is not login, redirect to login with status
                session()->flash('status', __('auth.verify_email_text'));
            @endphp
            <script>window.location.href = '{{ route('login') }}'</script>
        @elseif(session()->has('status'))
            <div class="alert success" role="alert">{{ session('status') }}</div>
        @endif
        @yield('content')
    </div>
</div>
@stack('scripts')
</body>
</html>
