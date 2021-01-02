<?php

namespace App\View\Composers;

use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LanguageComposer
{

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('languages', DB::table('languages')->get());
    }
}
