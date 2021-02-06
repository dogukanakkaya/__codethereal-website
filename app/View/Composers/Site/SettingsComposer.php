<?php

namespace App\View\Composers\Site;

use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SettingsComposer
{

    /**
     * Bind data to the view.
     *
     * @param View $view
     * @return void
     * @throws \Exception
     */
    public function compose(View $view)
    {
        $view->with('settings',
            cache()->remember('settings', 60 * 60 * 24, fn () =>
                DB::table('settings')
                    ->where('language', app()->getLocale())
                    ->whereIn('name', config('site.setting_names'))
                    ->get()
                    ->keyBy('name')
                    ->pluck('value', 'name')
            )
        );
    }
}
