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
        \Form::macro('save', function(array $options = []) {
            $opt = '';
            foreach ($options as $key => $value) {
                $opt .= $key.'="' . $value . '"';
            }

            return new HtmlString('<button type="submit" class="btn btn-primary" '.$opt.'>
                        <span class="btn-enabled">'. __("global.save") .' <i class="material-icons-outlined md-18">save</i></span>
                        <span class="btn-disabled d-none">'. __("global.loading") .' <i class="material-icons-outlined md-18">timeline</i></span>
                    </button>');
        });

        \Form::macro('closeBtn', function($options) {
            $opt = '';
            foreach ($options as $key => $value) {
                $opt .= $key.'="' . $value . '"';
            }

            return new HtmlString('<button type="button" class="btn btn-danger" '.$opt.'>'. __("global.close") .' <i class="material-icons-outlined md-18">close</i></button>');
        });
    }
}
