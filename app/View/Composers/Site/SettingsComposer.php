<?php

namespace App\View\Composers\Site;

use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SettingsComposer
{

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('settings',
            DB::table('settings')
                ->where('language', app()->getLocale())
                ->whereIn('name', config('site.setting_names'))
                ->get()
                ->keyBy('name')
                ->pluck('value', 'name')
        );
    }
}
