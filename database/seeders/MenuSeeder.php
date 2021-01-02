<?php

namespace Database\Seeders;

use App\Models\Admin\Menu\Group;
use App\Models\Admin\Menu\Item;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* Admin General Menus */
        $groupGeneral = Group::create([
            'id' => 1,
            'title' => 'Admin General Menus'
        ]);
        $itemHome = Item::create([
            'group_id' => $groupGeneral->id,
        ]);
        $itemSettings = Item::create([
            'group_id' => $groupGeneral->id,
            'permission' => 'settings'
        ]);
        $itemUsers = Item::create([
            'group_id' => $groupGeneral->id,
            'permission' => 'users'
        ]);
        $itemMenus = Item::create([
            'group_id' => $groupGeneral->id,
            'permission' => 'menus'
        ]);
        DB::table('menu_item_translations')->insert([
            [
                'item_id' => $itemHome->id,
                'title' => 'Anasayfa',
                'url' => 'admin',
                'icon' => 'home',
                'language' => 'tr'
            ],
            [
                'item_id' => $itemHome->id,
                'title' => 'Home',
                'url' => 'admin',
                'icon' => 'home',
                'language' => 'en'
            ],
            [
                'item_id' => $itemSettings->id,
                'title' => 'Ayarlar',
                'url' => 'admin/settings',
                'icon' => 'settings',
                'language' => 'tr'
            ],
            [
                'item_id' => $itemSettings->id,
                'title' => 'Settings',
                'url' => 'admin/settings',
                'icon' => 'settings',
                'language' => 'en'
            ],
            [
                'item_id' => $itemUsers->id,
                'title' => 'Üyeler',
                'url' => 'admin/users',
                'icon' => 'people_alt',
                'language' => 'tr'
            ],
            [
                'item_id' => $itemUsers->id,
                'title' => 'Users',
                'url' => 'admin/users',
                'icon' => 'people_alt',
                'language' => 'en'
            ],
            [
                'item_id' => $itemMenus->id,
                'title' => 'Menüler',
                'url' => 'admin/menus',
                'icon' => 'menu',
                'language' => 'tr'
            ],
            [
                'item_id' => $itemMenus->id,
                'title' => 'Menus',
                'url' => 'admin/menus',
                'icon' => 'menu',
                'language' => 'en'
            ]
        ]);
        /* /Admin General Menus */

        /* Admin CMS Menus */
        $groupCms = Group::create([
            'id' => 2,
            'title' => 'Admin CMS Menus'
        ]);
        $itemContents = Item::create([
            'group_id' => $groupCms->id,
            'permission' => 'contents'
        ]);
        DB::table('menu_item_translations')->insert([
            [
                'item_id' => $itemContents->id,
                'title' => 'İçerikler',
                'url' => 'admin/contents',
                'icon' => 'layers',
                'language' => 'tr'
            ],
            [
                'item_id' => $itemContents->id,
                'title' => 'Contents',
                'url' => 'admin/contents',
                'icon' => 'layers',
                'language' => 'en'
            ]
        ]);
        /* /Admin CMS Menus */
    }
}
