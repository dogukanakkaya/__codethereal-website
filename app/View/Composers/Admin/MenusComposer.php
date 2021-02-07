<?php

namespace App\View\Composers\Admin;

use Illuminate\Support\Facades\DB;
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
        $view->with('menuGroups', cache()->remember('admin-menu', 60 * 60 * 6, fn () =>
            DB::table('menu_items')
                ->select('title', 'url', 'icon', 'group_id', 'permission')
                ->oldest('sequence')
                ->latest()
                ->leftJoin('menu_item_translations', 'menu_item_translations.item_id', 'menu_items.id')
                ->where('language', app()->getLocale())
                ->whereNull('menu_items.deleted_at')
                ->whereIn('group_id', config('admin.menu_groups'))
                ->get()
                ->groupBy('group_id')
        ));
    }
}
