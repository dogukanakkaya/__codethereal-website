<?php

namespace App\View\Composers\Site;

use App\Models\Admin\Post\Post;
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
            Post::findSubPostsByLocale(config('site.categories'), ['posts.id', 'title', 'url'])
        );
    }
}
