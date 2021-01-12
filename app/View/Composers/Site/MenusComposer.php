<?php

namespace App\View\Composers\Site;

use App\Models\Admin\Menu\Group;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MenusComposer
{

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('headerMenus',
            buildTree(Group::itemsByLocale(config('site.header_menu'), 'id', 'title', 'url'), [
                'id' => 'item_id',
                'parentId' => 'parent_id'
            ])
        );
    }
}
