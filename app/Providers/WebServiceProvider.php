<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class WebServiceProvider extends ServiceProvider
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
        // Site View Composers
        view()->composer('site.*', 'App\View\Composers\Site\SettingsComposer'); // Settings composer

        view()->composer('site.*', 'App\View\Composers\Site\MenusComposer'); // Header, footer menus composer
        // /Site View Composers
    }
}
