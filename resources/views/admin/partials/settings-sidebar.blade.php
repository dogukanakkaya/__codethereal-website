<div class="settings-sidebar">
    <div class="head">
        <h4>Hello World!</h4>
        <a href="javascript:void(0);" onclick="toggleThemeSettings()" class="danger"><i class="material-icons-outlined md-18">close</i></a>
    </div>
    <div class="p-3">
        <div class="theme-style">
            <div class="item mb-5">
                <img src="{{ asset('img/light-theme.png') }}" alt="light-theme" class="w-100 mb-3">
                <div>
                    <input name="theme_color" type="radio" id="light-theme" onchange="toggleThemeColor('light-theme')">
                    <label for="light-theme">{{ __('global.light_theme') }}</label>
                </div>
            </div>
            <div class="item mb-5">
                <img src="{{ asset('img/dark-theme.png') }}" alt="dark-theme" class="w-100 mb-3">
                <div>
                    <input name="theme_color" type="radio" id="dark-theme" onchange="toggleThemeColor('dark-theme')">
                    <label for="dark-theme">{{ __('global.dark_theme') }}</label>
                </div>
            </div>
            <hr>
            <div class="item mb-5">
                <img src="{{ asset('img/light-theme.png') }}" alt="with-bg" class="w-100 mb-3">
                <div>
                    <input name="sidebar_theme" type="radio" id="with-bg" onchange="toggleSidebarStyle('with-bg')">
                    <label for="with-bg">{{ __('global.sidebar_with_bg') }}</label>
                </div>
            </div>
            <div class="item mb-5">
                <img src="{{ asset('img/sidebar-without-bg.png') }}" alt="without-bg" class="w-100 mb-3">
                <div>
                    <input name="sidebar_theme" type="radio" id="without-bg" onchange="toggleSidebarStyle('without-bg')">
                    <label for="without-bg">{{ __('global.sidebar_without_bg') }}</label>
                </div>
            </div>
        </div>
    </div>
</div>
