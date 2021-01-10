<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Admin\Menu\Group;

class WebController extends Controller
{
    public function index()
    {
        $data = [
            'headerMenus' => buildTree(Group::itemsByLocale(config('site.header_menu'), 'id', 'title'), [
                'id' => 'item_id',
                'parentId' => 'parent_id'
            ])
        ];
        return view('site.index', $data);
    }
}
