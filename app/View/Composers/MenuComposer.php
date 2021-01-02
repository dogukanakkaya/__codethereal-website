<?php

namespace App\View\Composers;

use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MenuComposer
{

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $menuGroups = DB::table('menu_items')
            ->select('title', 'url', 'icon', 'group_id', 'permission')
            ->oldest('sequence')
            ->latest()
            ->leftJoin('menu_item_translations', 'menu_item_translations.item_id', 'menu_items.id')
            ->where('language', app()->getLocale())
            ->whereIn('group_id', config('admin.menu_groups'))
            ->get()
            ->groupBy('group_id');
        $view->with('menuGroups', $menuGroups);
    }
}
