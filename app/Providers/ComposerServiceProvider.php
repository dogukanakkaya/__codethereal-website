<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Using class based composers...
        view()->composer(['admin.layouts.base', 'admin.profile.index'], 'App\View\Composers\UserComposer');

        view()->composer(['admin.settings.index', 'admin.menus.items', 'admin.partials.settings-sidebar'], 'App\View\Composers\LanguagesComposer');
    }
}
