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
        body,
        body *:not(html):not(style):not(br):not(tr):not(code) {
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif,
            'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';
            position: relative;
        }

        body {
            -webkit-text-size-adjust: none;
            color: #718096;
            height: 100%;
            line-height: 1.4;
            margin: 0;
            padding: 0;
            width: 100% !important;
            background: #fff;
        }

        p,
        ul,
        ol,
        blockquote {line-height: 1.4;text-align: left;}
        a {color: #3869d4;}
        a img {border: none;}
        h1 {color: #3d4852;font-size: 18px;font-weight: bold;margin-top: 0;text-align: left;}
        h2 {font-size: 16px;font-weight: bold;margin-top: 0;text-align: left;}
        h3 {font-size: 14px;font-weight: bold;margin-top: 0;text-align: left;}
        p {font-size: 16px;line-height: 1.5em;margin-top: 0;text-align: left;}
        .container{background: #edf2f7;}
        .inner{width: 570px;margin: 0 auto; box-shadow: 0 2px 0 rgba(0, 0, 150, 0.025), 2px 4px 0 rgba(0, 0, 150, 0.015);border-color: #e8e5ef;border-radius: 2px;border-width: 1px;background: #fff;padding: 30px;}
        .line{height: 1px;background-color: rgb(241, 241, 241);margin-top: 10px;margin-bottom: 10px;}
        img{max-width: 100%}
        .text-center{text-align:center;}
        .bottom-text {color: rgb(128, 128, 128);padding: 20px;}
        .bottom-text * {font-size: 11px !important;}
        .bottom-text img {width: 50px;vertical-align: middle;margin-left: 5px;margin-right: 5px}
        .logo {width: 100%;text-align: center;padding: 20px 0;}
        .logo img {width: 150px !important;}
        .w-100{width: 100%;}
        .link-text{word-break: break-all;}
        .click-button{
            border-radius: 4px;
            color: #fff;
            display: inline-block;
            overflow: hidden;
            text-decoration: none;
            background-color: #2d3748;
            border-bottom: 8px solid #2d3748;
            border-left: 18px solid #2d3748;
            border-right: 18px solid #2d3748;
            border-top: 8px solid #2d3748;
            margin: 1rem 0;
            text-align: center;
            font-size: 14px;
        }

        .text-center{text-align:center;}
        .text-bold{font-weight: bold;}
        .text-dark{color: #5A5858;}
        .text-gray{color: #767678;}
        .list-type{margin: 1rem 0;}
        .list-type p{margin: 0;}
        @media only screen and (max-width: 690px) {
            .inner{font-size: 11px;line-height: 18px;}
            .logo img {width: 85px !important;line-height: 0;}
        }
    </style>
    @yield('styles')
</head>
<body>
<div class="container">
    <div class="logo">
        <img src="{{ asset('img/logo-dark.svg') }}" alt="logo"/>
    </div>
    <div class="inner">
        @yield('content')
    </div>
    <div class="bottom-text text-center">
        <span>{{ __('mail.info_text') }}</span>
        <a href="https://codethereal.com/" target="_blank">
            <img src="{{ asset('img/logo-dark.svg') }}" alt="logo"/>
        </a>
        <span>{{ __('mail.info_text2') }}</span>
    </div>
</div>
</body>
</html>
