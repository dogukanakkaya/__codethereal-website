<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $settings['title'] ?? config('app.name'))</title>
    <link rel="stylesheet" href="{{ asset('site/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('site/bootstrap-icons/bootstrap-icons.css') }}">
</head>
<body>
<div class="container">
    <div class="top-bar d-flex justify-content-between align-items-center">
        <ul>
            <li><a href="{{ $settings['linkedin'] ?? '#' }}"><i class="bi bi-linkedin linkedin"></i></a></li>
            <li><a href="{{ $settings['github'] ?? '#' }}"><i class="bi bi-github github"></i></a></li>
            <li><a href="{{ $settings['youtube'] ?? '#' }}"><i class="bi bi-youtube youtube"></i></a></li>
        </ul>
        <ul>
            @if(!auth()->check())
            <li onclick="__login()"><i class="bi bi-person"></i> Login</li>
            <span class="bracket"></span>
            <li onclick="__register()"><i class="bi bi-person-plus"></i> Register</li>
            <span class="bracket"></span>
            @else
                <li onclick="document.getElementById('logout-form').submit();"><i class="bi bi-person-x"></i> Logout</li>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
                <span class="bracket"></span>
            @endif
            @foreach($languages as $language)
                @if(app()->getLocale() === $language->code)
                    @continue
                @endif
                    <li>
                        <a href="{{ LaravelLocalization::getLocalizedURL($language->code) }}">
                            <img src="{{ asset('img/flags') }}/{{ $language->code }}.svg" width="20" alt="{{ $language->name }}">
                            {{ strtoupper($language->code) }}
                        </a>
                    </li>
            @endforeach
        </ul>
    </div>
</div>
<header>
    <div class="container">
        <input type="checkbox" id="menu-toggle"/>
        <nav class="d-flex justify-content-between">
            <div class="logo logo-white">
                <a href="{{ route('web.index') }}">
                    <img src="{{ asset('site/img/logo-light.svg') }}" alt="">
                </a>
            </div>
            <div class="logo logo-dark">
                <a href="{{ route('web.index') }}">
                    <img src="{{ asset('site/img/logo-dark.svg') }}" alt="">
                </a>
            </div>
            <ul class="menus">
                @foreach($headerMenus as $hMenu)
                    @if(count($hMenu->children))
                        <li class="has-children">
                            <a href="{{ createUrl($hMenu->url) }}">{{ $hMenu->title }}</a>
                            <ul class="sub-menus">
                                @foreach($hMenu->children as $cMenu)
                                <li><a href="{{ createUrl($cMenu->url) }}">{{ $cMenu->title }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                    @else
                        <li><a href="{{ createUrl($hMenu->url) }}">{{ $hMenu->title }}</a></li>
                    @endif
                @endforeach
                <li>
                    <button>
                        Extra Button
                    </button>
                </li>
            </ul>
            <label for="menu-toggle">
                <i class="bi bi-list-nested"></i>
            </label>
        </nav>
    </div>
</header>
@yield('content')
<footer>
    <div class="container pt-5 pb-5">
        <div class="row gy-4 gy-lg-0">
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="logo">
                    <a href="#"><img src="{{ asset('site/img/logo-light.svg') }}" alt=""></a>
                </div>
                <p>{{ $settings['description'] ?? '' }}</p>
                <ul class="d-flex justify-content-center justify-content-md-start">
                    <li><a href="{{ $settings['linkedin'] ?? '#' }}" class="ps-0"><i class="bi bi-linkedin"></i></a></li>
                    <li><a href="{{ $settings['github'] ?? '#' }}"><i class="bi bi-github"></i></a></li>
                    <li><a href="{{ $settings['youtube'] ?? '#' }}"><i class="bi bi-youtube"></i></a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <h5><a href="{{ url($category->url) }}"><i class="bi bi-list"></i> {{ $category->title }}</a></h5>
                <ul>
                    @foreach($categoryItems as $categoryItem)
                        <li><a href="{{ url($categoryItem->url) }}"><i class="bi bi-chevron-double-right"></i> {{ $categoryItem->title }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <h5><a href=""><i class="bi bi-link-45deg"></i> Quick Links</a></h5>
                <ul>
                    @foreach($quickLinks as $quickLink)
                        <li><a href="{{ url($quickLink->url) }}"><i class="bi bi-chevron-double-right"></i> {{ $quickLink->title }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <h5><a href="{{ url('iletisim-sayfasi-route') }}"><i class="bi bi-cursor"></i> Contact Us</a></h5>
                <ul>
                    <li><a href="{{ $settings['phone'] ?? '' }}"><i class="bi bi-phone-vibrate"></i> {{ $settings['phone'] ?? '' }}</a></li>
                    <li><a href="{{ $settings['email'] ?? '' }}"><i class="bi bi-envelope"></i> {{ $settings['email'] ?? '' }}</a></li>
                    <li><a href="{{ $settings['address'] ?? '' }}"><i class="bi bi-geo-alt"></i> {{ $settings['address'] ?? '' }}</a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>
<script>const url = (path) => `{{ url('') }}/${path}`</script>
<script src="{{ asset('site/js/app.js') }}"></script>
</body>
</html>
