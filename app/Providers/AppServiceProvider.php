<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
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
        \Form::macro('save', function() {
            return '<button type="submit" class="btn btn-primary">
                        <span class="btn-enabled">'. __("global.save") .' <i class="material-icons-outlined md-18">save</i></span>
                        <span class="btn-disabled d-none">'. __("global.loading") .' <i class="material-icons-outlined md-18">timeline</i></span>
                    </button>';
        });
    }
}
