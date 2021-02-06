<?php

namespace App\View\Composers\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LanguagesComposer
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
        $view->with('languages',
            cache()->remember('languages', 60 * 60 * 24, fn () =>
                DB::table('languages')->get()
            )
        );
    }
}
