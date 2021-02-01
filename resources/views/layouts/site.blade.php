<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    {!! meta($_meta ?? $settings) !!}

    <link rel="stylesheet" href="{{ asset('site/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('site/bootstrap-icons/bootstrap-icons.css') }}">
    @stack('styles')
</head>
<body>
<div id="app">
    <div class="container">
        <div class="top-bar d-flex justify-content-between align-items-center">
            <ul>
                <li><a href="{{ $settings['linkedin'] ?? '#' }}"><i class="bi bi-linkedin linkedin"></i></a></li>
                <li><a href="{{ $settings['github'] ?? '#' }}"><i class="bi bi-github github"></i></a></li>
                <li><a href="{{ $settings['youtube'] ?? '#' }}"><i class="bi bi-youtube youtube"></i></a></li>
            </ul>
            <ul>
                @if(!auth()->check())
                <li onclick="__login()"><i class="bi bi-person"></i> {{ __('site.login') }}</li>
                <span class="bracket"></span>
                <li onclick="__register()"><i class="bi bi-person-plus"></i> {{ __('site.register') }}</li>
                <span class="bracket"></span>
                @else
                    <li onclick="document.getElementById('logout-form').submit();"><i class="bi bi-person-x"></i> {{ __('site.logout') }}</li>
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
    {{--
    @php($logoLight = resizeById($settings['logo_light']))
    @php($logoDark = resizeById($settings['logo_dark']))
    --}}
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
                            {{ __('site.contact_us') }}
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
    <footer style="background: linear-gradient(to right,rgba(12, 41, 116, 0.84) 0%,rgba(35, 107, 237, 0.84) 48%), url({{ resize($footer->featured_image ?? '', 1500) }}) no-repeat;background-size: cover;">
        <div class="container pt-5 pb-5">
            <div class="row gy-4 gy-lg-0">
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="logo">
                        <a href="{{ route('web.index') }}"><img src="{{ asset('site/img/logo-light.svg') }}" alt=""></a>
                    </div>
                    <p>{{ $footer->description ?? '' }}</p>
                    <ul class="d-flex justify-content-center justify-content-md-start">
                        <li><a href="{{ $settings['linkedin'] ?? '#' }}" class="ps-0"><i class="bi bi-linkedin"></i></a></li>
                        <li><a href="{{ $settings['github'] ?? '#' }}"><i class="bi bi-github"></i></a></li>
                        <li><a href="{{ $settings['youtube'] ?? '#' }}"><i class="bi bi-youtube"></i></a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <h5><a href=""><i class="bi bi-list"></i> {{ __('site.categories') }}</a></h5>
                    <ul>
                        @foreach($categoryLinks as $categoryLink)
                            <li><a href="{{ createUrl($categoryLink->url) }}"><i class="bi bi-chevron-double-right"></i> {{ $categoryLink->title }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <h5><a href=""><i class="bi bi-link-45deg"></i> {{ __('site.quick_links') }}</a></h5>
                    <ul>
                        @foreach($quickLinks as $quickLink)
                            <li><a href="{{ url($quickLink->url) }}"><i class="bi bi-chevron-double-right"></i> {{ $quickLink->title }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <h5><a href="{{ url('iletisim-sayfasi-route') }}"><i class="bi bi-cursor"></i> {{ __('site.contact_us') }}</a></h5>
                    <ul>
                        <li><a href="tel:{{ $settings['phone'] ?? '' }}"><i class="bi bi-phone-vibrate"></i> {{ $settings['phone'] ?? '' }}</a></li>
                        <li><a href="mailto:{{ $settings['email'] ?? '' }}"><i class="bi bi-envelope"></i> {{ $settings['email'] ?? '' }}</a></li>
                        <li><a href="{{ $settings['address'] ?? '' }}"><i class="bi bi-geo-alt"></i> {{ $settings['address'] ?? '' }}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</div>
<script>const url = path => `{{ url('') }}/${path}`</script>
<script src="{{ asset('site/js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
