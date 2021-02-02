<?php

namespace Database\Seeders;

use App\Models\Admin\Menu\MenuGroup;
use App\Models\Admin\Menu\MenuItem;
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
        $groupGeneral = MenuGroup::create([
            'id' => 1,
            'title' => 'Admin General Menus'
        ]);
        $itemHome = MenuItem::create([
            'group_id' => $groupGeneral->id,
        ]);
        $itemSettings = MenuItem::create([
            'group_id' => $groupGeneral->id,
            'permission' => 'settings'
        ]);
        $itemUsers = MenuItem::create([
            'group_id' => $groupGeneral->id,
            'permission' => 'users'
        ]);
        $itemMenus = MenuItem::create([
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
        $groupCms = MenuGroup::create([
            'id' => 2,
            'title' => 'Admin CMS Menus'
        ]);
        $itemContents = MenuItem::create([
            'group_id' => $groupCms->id,
            'permission' => 'contents'
        ]);
        DB::table('menu_item_translations')->insert([
            [
                'item_id' => $itemContents->id,
                'title' => 'İçerikler',
                'url' => 'admin/posts',
                'icon' => 'layers',
                'language' => 'tr'
            ],
            [
                'item_id' => $itemContents->id,
                'title' => 'Posts',
                'url' => 'admin/posts',
                'icon' => 'layers',
                'language' => 'en'
            ]
        ]);
        /* /Admin CMS Menus */
    }
}
