<?php

namespace App\View\Composers\Site;

use App\Models\Post\Post;
use App\Repositories\Interfaces\PostRepositoryInterface;
use Illuminate\View\View;

class CategoriesComposer
{
    public function __construct(private PostRepositoryInterface $postRepository){}

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('categoryLinks',
            $this->postRepository->children(config('site.categories'), ['posts.id', 'title', 'url'])
        );
    }
}
