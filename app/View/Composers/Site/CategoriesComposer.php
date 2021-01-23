<?php

namespace App\View\Composers\Site;

use App\Models\Admin\Content\Content;
use Illuminate\View\View;

class CategoriesComposer
{

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('categoryLinks',
            Content::findSubContentsByLocale(config('site.categories'), ['contents.id', 'title', 'url'])
        );
    }
}
