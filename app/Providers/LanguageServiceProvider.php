<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class LanguageServiceProvider extends ServiceProvider
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
        $languages = languages();
        foreach ($languages as $language) {
            $supportedLocales[$language->code] = [
                'name' => $language->name,
                'script' => '',
                'native' => '',
                'regional' => '',
            ];
            config()->set('laravellocalization.supportedLocales', $supportedLocales);
        }
    }
}
