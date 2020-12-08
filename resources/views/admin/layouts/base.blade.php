@spaceless
    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Codethereal | Manage')</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>
<div id="loader">
    <span>{</span><span>};</span>
</div>
<input type="checkbox" id="menu-toggle" checked/>
<div class="top-container">
    <aside style="background-image: url('{{ asset('img/sidebar-bg.jpg') }}')">
        <div class="overlay">
            <div class="logo">
                <a href="">
                    <img src="{{ asset('img/Logo.png') }}" alt=""/>
                </a>
            </div>
            <div class="menus">
                <ul>
                    <li class="seperator">{{ __('global.general') }}</li>
                    <li>
                        <a href="{{ route('admin.home') }}" class="{{ isActive('admin') }}"><i
                                class="material-icons-outlined md-18">home</i> {{ __('global.home') }}</a>
                    </li>
                    @can('see_settings')
                        <li>
                            <a href="{{ route('settings.index') }}" class="{{ isActive('admin/settings') }}"><i
                                    class="material-icons-outlined md-18">settings</i> Settings</a>
                        </li>
                    @endcan
                    @can('see_users')
                        <li>
                            <a href="{{ route('users.index') }}" class="{{ isActive('admin/users') }}"><i
                                    class="material-icons-outlined md-18">people_alt</i> Users</a>
                        </li>
                    @endcan
                    @can('see_menus')
                        <li>
                            <a href="{{ route('menus.index') }}"
                               class="{{ isActive(['admin/menus', 'admin/menus/*/items']) }}"><i
                                    class="material-icons-outlined md-18">menu</i> Menus</a>
                        </li>
                    @endcan

                    <li class="seperator">{{ __('global.cms') }}</li>
                    @can('see_contents')
                        <li>
                            <a href="{{ route('contents.index') }}"
                               class="{{ isActive(['admin/contents', 'admin/contents/*']) }}"><i
                                    class="material-icons-outlined md-18">layers</i> Contents</a>
                        </li>
                    @endcan
                    <li class="has-dd">
                        <a href="javascript:void(0);"><i class="material-icons-outlined md-18">keyboard_tab</i> Other</a>
                        <ul class="menu-dd">
                            <li class="menu-dd-item">
                                <a href="#">Text</a>
                            </li>
                            <li class="menu-dd-item">
                                <a href="#">File</a>
                            </li>
                        </ul>
                    </li>

                    @if($user->isDev())
                        <li class="seperator">{{ __('global.developer') }}</li>
                        <li>
                            <a href="{{ url('admin/dev/permissions') }}"
                               class="{{ isActive('admin/dev/permissions') }}"><i class="material-icons-outlined md-18">security</i>
                                Permissions</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </aside>
    <header>
        <label for="menu-toggle">
            <i class="material-icons-outlined md-36">menu</i>
        </label>
        <div class="top-right">
            <ul>
                <li>
                    <input type="search" placeholder="{{ __('global.search') }}...">
                    <a href="javascript:void(0);"><i class="material-icons-outlined">search</i></a>
                </li>
                <li>
                    <a class="live-time" href="javascript:void(0);"><i class="material-icons-outlined">timer</i>
                        <span>{{ now()->format('H:i:s') }}</span></a>
                </li>
                <li class="language">
                    <div class="dropdown">
                        <button class="dropdown-toggle" type="button" id="dropdownMenuButton"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <a href="javascript:void(0);"><img src="{{ asset('img/flags') }}/{{ app()->getLocale() }}.svg" alt=""></a>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            @foreach($languages as $language)
                                @if(app()->getLocale() === $language->code)
                                    @continue
                                @endif
                                <a class="dropdown-item" href="{{ LaravelLocalization::getLocalizedURL($language->code) }}">
                                    <img class="flag-img" src="{{ asset('img/flags') }}/{{ $language->code }}.svg" alt=""> {{ $language->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </li>
                <li>
                    <a href="javascript:void(0);">
                        <i class="material-icons-outlined">notifications</i>
                    </a>
                    <span class="total-notification {{ true ? 'blink' : '' }}">9</span>
                </li>
                <li>
                    <a href="javascript:void(0);" onclick="toggleTheme()">
                        <i class="material-icons-outlined">nights_stay</i>
                    </a>
                </li>
                <li class="profile">
                    <div class="dropdown">
                        <button class="dropdown-toggle" type="button" id="dropdownMenuButton"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @php
                                $file = App\Models\Admin\File::find($user->image);
                                $image = isset($file->path) ? 'storage/' . $file->path : 'img/profile.webp';
                            @endphp
                            <img src="{{ asset($image) }}" alt="profile">
                            <span class="ml-1 d-none d-xl-inline-block">{{ Str::words($user->name, 1, '') }}</span>
                            <i class="d-none d-xl-inline-block material-icons-outlined md-18">expand_more</i>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="{{ route('profile.index') }}"><i
                                    class="material-icons-outlined md-18">person</i> {{ __('users.profile') }}</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="javascript:void(0);"
                               onclick="document.getElementById('logout-form').submit();"><i
                                    class="material-icons-outlined md-18">exit_to_app</i> {{ __('users.logout') }}</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </div>
                    </div>
                </li>
                <li>
                    <a href="javascript:void(0);" onclick="toggleThemeSettings()">
                        <i class="material-icons-outlined">settings</i>
                    </a>
                </li>
            </ul>
        </div>
    </header>
</div>

<main>
    @yield('content')
</main>

<div class="bottom-container"></div>

@include('admin.partials.settings-sidebar')

<div class="black-overlay"></div>

<div class="ce-toast-container"></div>

<div class="quick-alert">
    <div class="icon"><i class="material-icons-outlined md-24">warning</i></div>
    <div class="text"><p></p></div>
</div>

@stack('toEnd')
<script>
    const asset = (path) => `{{ asset('') }}${path}`
    const storage = (path) => `{{ asset('storage') }}/${path}`
</script>
<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')

</body>
</html>
@endspaceless
