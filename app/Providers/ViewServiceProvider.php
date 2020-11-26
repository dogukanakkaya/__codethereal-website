<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
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
        View::composer(['admin.layouts.base', 'admin.profile.index'], 'App\View\Composers\UserComposer');

        View::composer(['admin.settings.index', 'admin.menus.items'], 'App\View\Composers\LanguagesComposer');
    }
}
