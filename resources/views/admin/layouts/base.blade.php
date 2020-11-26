<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Codethereal | Manage')</title>

    <link rel="stylesheet" href="{{ asset('css/ce/toast.css') }}">
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
            <div class="user-profile">
                <div class="image">
                    @php
                    $file = App\Models\Admin\File::find($user->image);
                    $image = isset($file->path) ? 'storage/' . $file->path : 'img/profile.png';
                    @endphp
                    <img src="{{ asset($image) }}" alt="profile">
                </div>
                <div class="info">
                    <a href="{{ route('profile.index') }}" class="{{ isActive('admin/profile') }}">{{ $user->name }}</a>
                    <p class="mt-2 mb-0">{{ $user->position }}</p>
                </div>
                <div class="buttons mt-3">
                    @if (Route::has('profile.index'))
                        <a href="{{ route('profile.index') }}"><i class="fas fa-cog"></i></a>
                    @endif
                    @if (Route::has('logout'))
                            <a href="javascript:void(0);" class="danger" onclick="document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i></a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                    @endif
                </div>
            </div>
            <div class="menus">
                <ul>
                    <li class="seperator">{{ __('global.general') }}</li>
                    <li>
                        <a href="{{ route('admin.home') }}" class="{{ isActive('admin') }}"><i class="fas fa-brain"></i> {{ __('global.home') }}</a>
                    </li>
                    @can('see_settings')
                    <li>
                        <a href="{{ route('settings.index') }}"  class="{{ isActive('admin/settings') }}"><i class="fas fa-cogs"></i> Settings</a>
                    </li>
                    @endcan
                    <li>
                        <a href="{{ route('users.index') }}" class="{{ isActive('admin/users') }}"><i class="fas fa-users"></i> Users</a>
                    </li>
                    <li>
                        <a href="{{ route('menus.index') }}" class="{{ isActive(['admin/menus', 'admin/menus/*/items']) }}"><i class="fas fa-bars"></i> Menus</a>
                    </li>
                    @if($user->isDev())
                    <li class="seperator">{{ __('global.developer') }}</li>
                    <li>
                        <a href="{{ url('admin/dev/permissions') }}" class="{{ isActive('admin/dev/permissions') }}"><i class="fas fa-user-tag"></i> Permissions</a>
                    </li>
                    @endif
                    <!--
                    <li class="has-dropdown">
                      <a href="javascript:void(0);"
                        ><i class="fas fa-share-alt"></i> Forms</a
                      >
                      <ul class="dropdown">
                        <li class="dropdown-item">
                          <a href="form-text.html">Text</a>
                        </li>
                        <li class="dropdown-item">
                          <a href="form-file.html">File</a>
                        </li>
                      </ul>
                    </li>
                    -->
                </ul>
            </div>
        </div>
    </aside>
    <header>
        <label for="menu-toggle">
            <div class="menu-bar">
                <div class="bar1"></div>
                <div class="bar2"></div>
                <div class="bar3"></div>
            </div>
        </label>
        <div class="top-right">
            <ul>
                <li>
                    <input type="search" placeholder="{{ __('global.search') }}...">
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-bell"></i>
                    </a>
                    <span class="total-notification">9</span>
                </li>
                <li>
                    <a href="javascript:void(0);" onclick="toggleThemeSettings()">
                        <i class="fas fa-cog"></i>
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

<script>
    const asset = (path) => `{{ asset('') }}${path}`
    const storage = (path) => `{{ asset('storage') }}/${path}`
</script>
<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')

</body>
</html>
