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
        <div class="logo"><img src="{{ asset('img/logo-dark.svg') }}" alt=""></div>
        @if($errors->any())
            <div class="alert error">
                <p>{{$errors->first()}}</p>
            </div>
        @elseif(session()->has('resent'))
            <div class="alert success" role="alert">{{ session('resent') }}</div>
        @elseif(session()->has('status'))
            <div class="alert success" role="alert">{{ session('status') }}</div>
        @endif
        @yield('content')
    </div>
</div>
@stack('scripts')
</body>
</html>
