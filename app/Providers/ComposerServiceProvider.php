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
        view()->composer('*', 'App\View\Composers\Admin\LanguagesComposer'); // Languages composer

        // Admin View Composers
        view()->composer(['layouts.admin', 'admin.profile.index'], 'App\View\Composers\Admin\UserComposer'); // Authenticated user composer

        view()->composer('admin.*', 'App\View\Composers\Admin\MenusComposer'); // Admin menus composer
        // /Admin View Composers
    }
}
