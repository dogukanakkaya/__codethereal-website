<div class="settings-sidebar">
    <div class="head">
        <h4>{{ __('global.theme_settings') }}</h4>
        <a href="javascript:void(0);" onclick="toggleThemeSettings()" class="danger"><i class="fas fa-times"></i></a>
    </div>
    <div class="p-3">
        <div class="theme-color">
            <div class="item mb-5">
                <img src="{{ asset('img/light-theme.png') }}" alt="light-theme" class="w-100 mb-3">
                <div>
                    <input name="theme_color" type="radio" id="light-theme" onchange="toggleTheme('light-theme')">
                    <label for="light-theme">{{ __('global.light_theme') }}</label>
                </div>
            </div>
            <div class="item mb-5">
                <img src="{{ asset('img/dark-theme.png') }}" alt="dark-theme" class="w-100 mb-3">
                <div>
                    <input name="theme_color" type="radio" id="dark-theme" onchange="toggleTheme('dark-theme')">
                    <label for="dark-theme">{{ __('global.dark_theme') }}</label>
                </div>
            </div>
        </div>
    </div>
</div>
