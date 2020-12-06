<?php

namespace App\Providers;

use Illuminate\Support\HtmlString;
use Illuminate\Support\ServiceProvider;
use JetBrains\PhpStorm\Pure;

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
        \Form::macro('save', function (array $attributes = []) {
            $existAttrs = [
                'type' => 'submit',
                'class' => 'btn btn-primary'
            ];
            $mergedAttrs = mergeHtmlAttributes($existAttrs, $attributes);

            return new HtmlString('<button ' . $mergedAttrs . '>
                        <span class="btn-enabled">' . __("global.save") . ' <i class="material-icons-outlined md-18">save</i></span>
                        <span class="btn-disabled d-none">' . __("global.loading") . ' <i class="material-icons-outlined md-18">timeline</i></span>
                    </button>');
        });

        \Form::macro('closeBtn', function (array $attributes = []) {
            $existAttrs = [
                'type' => 'button',
                'class' => 'btn btn-danger'
            ];
            $mergedAttrs = mergeHtmlAttributes($existAttrs, $attributes);

            return new HtmlString('<button ' . $mergedAttrs . '>' . __("global.close") . ' <i class="material-icons-outlined md-18">close</i></button>');
        });

        \Form::macro('refresh', function (array $attributes = []) {
            $existAttrs = [
                'class' => 'btn btn-primary'
            ];
            $mergedAttrs = mergeHtmlAttributes($existAttrs, $attributes);

            return new HtmlString('<button ' . $mergedAttrs . '>' . __("global.refresh") . ' <i class="material-icons-outlined md-18 spin">sync</i></button>');
        });
    }
}
