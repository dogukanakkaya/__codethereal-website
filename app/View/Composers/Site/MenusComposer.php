<?php

namespace App\View\Composers\Site;

use App\Models\Menu\MenuGroup;
use Illuminate\View\View;

class MenusComposer
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
        $view->with('headerMenus',
            buildTree(
                cache()->remember('header-menu', 60 * 60 * 6, fn () =>
                    MenuGroup::itemsByLocale(config('site.header_menu'), 'item_id', 'parent_id', 'title', 'url')
                )
            )
        );

        $view->with('quickLinks',
            cache()->remember('quick-links', 60 * 60 * 6, fn () =>
                MenuGroup::itemsByLocale(config('site.quick_links'), 'item_id', 'title', 'url')
            )
        );
    }
}
