<?php

namespace App\Providers;

use Illuminate\Support\HtmlString;
use Illuminate\Support\ServiceProvider;

class MacroServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Render html without escaping
        $html = new HtmlString('<button type="submit" class="btn btn-primary">
                        <span class="btn-enabled">'. __("global.save") .' <i class="material-icons-outlined md-18">save</i></span>
                        <span class="btn-disabled d-none">'. __("global.loading") .' <i class="material-icons-outlined md-18">timeline</i></span>
                    </button>');
        \Form::macro('save', function() use($html) {
            return $html;
        });
    }
}
