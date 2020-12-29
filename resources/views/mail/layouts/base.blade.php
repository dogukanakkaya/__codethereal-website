<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <style>
        :root{
            --main-color: #71b6f9;
        }
        body{background: rgb(241, 241, 241);font-family: Arial;}
        a{text-decoration: none;color: #7f99ff;}
        .container{width: 90%;margin: 0 auto}
        .contain{margin-top: 50px;background: #fff;border: 2px solid rgb(224, 222, 222);box-shadow: rgb(226, 226, 226) -5px 6px 11px 3px;padding: 20px;}
        .content-body{font-size: 13px;color: #6f6f6f;line-height: 24px;}
        .line{height: 2px;background-color: rgb(241, 241, 241);margin-top: 10px;margin-bottom: 10px;}
        .mail-info-text {color: rgb(128, 128, 128);margin-top: 20px;}
        .mail-info-text * {font-size: 11px !important;}
        .mail-info-text img {width: 80px;vertical-align: middle;margin-left: 5px;margin-right: 5px}
        .logo {width: 100%;text-align: center;}
        .logo img {width: 150px!important;}
        .click-button{margin: 10px 0;text-align: center;}
        .click-button a{background: linear-gradient(45deg,var(--main-color),#00b8ff63);outline: none;border: none;color:white;padding: 7px 15px;display: block;}
        .text-center{text-align:center;}
        .text-bold{font-weight: bold;}
        .text-dark{color: #5A5858;}
        .text-gray{color: #767678;}
        .list-type{margin: 1rem 0;}
        .list-type p{margin: 0;}
        @media only screen and (max-width: 690px) {
            .content-body{font-size: 11px;line-height: 18px;}
            .logo img {width: 85px !important;line-height: 0;}
        }
    </style>
    @yield('styles')
</head>
<body>
<div class="container">
    <div class="contain">
        <div class="logo">
            <img src="{{ asset('img/logo.svg') }}" alt="logo"/>
        </div>
        <div class="line"></div>
        <div class="content-body">
            @yield('content')
        </div>
    </div>
    <div class="mail-info-text text-center">
        <span>{{ __('mail.info_text') }}</span>
        <a href="https://codethereal.com/" target="_blank">
            <img src="{{ asset('img/logo.svg') }}" alt="logo"/>
        </a>
        <span>{{ __('mail.info_text2') }}</span>
    </div>
</div>
</body>
</html>
