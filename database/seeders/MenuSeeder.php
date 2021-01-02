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
        $group = Group::create([
            'id' => 1,
            'title' => 'Admin General Menus'
        ]);
        $item = Item::create([
            'group_id' => $group->id,
        ]);
        $item2 = Item::create([
            'group_id' => $group->id,
            'permission' => 'settings'
        ]);
        $item3 = Item::create([
            'group_id' => $group->id,
            'permission' => 'users'
        ]);
        $item4 = Item::create([
            'group_id' => $group->id,
            'permission' => 'menus'
        ]);
        DB::table('menu_item_translations')->insert([
            [
                'item_id' => $item->id,
                'title' => 'Anasayfa',
                'url' => 'admin',
                'icon' => 'home',
                'language' => 'tr'
            ],
            [
                'item_id' => $item->id,
                'title' => 'Home',
                'url' => 'admin',
                'icon' => 'home',
                'language' => 'en'
            ],
            [
                'item_id' => $item2->id,
                'title' => 'Ayarlar',
                'url' => 'admin/settings',
                'icon' => 'settings',
                'language' => 'tr'
            ],
            [
                'item_id' => $item2->id,
                'title' => 'Settings',
                'url' => 'admin/settings',
                'icon' => 'settings',
                'language' => 'en'
            ],
            [
                'item_id' => $item3->id,
                'title' => 'Üyeler',
                'url' => 'admin/users',
                'icon' => 'people_alt',
                'language' => 'tr'
            ],
            [
                'item_id' => $item3->id,
                'title' => 'Users',
                'url' => 'admin/users',
                'icon' => 'people_alt',
                'language' => 'en'
            ],
            [
                'item_id' => $item4->id,
                'title' => 'Menüler',
                'url' => 'admin/menus',
                'icon' => 'menu',
                'language' => 'tr'
            ],
            [
                'item_id' => $item4->id,
                'title' => 'Menus',
                'url' => 'admin/menus',
                'icon' => 'menu',
                'language' => 'en'
            ]
        ]);
        /* /Admin General Menus */

        /* Admin CMS Menus */
        $group2 = Group::create([
            'id' => 2,
            'title' => 'Admin CMS Menus'
        ]);
        $item5 = Item::create([
            'group_id' => $group2->id,
            'permission' => 'contents'
        ]);
        DB::table('menu_item_translations')->insert([
            [
                'item_id' => $item5->id,
                'title' => 'İçerikler',
                'url' => 'admin/contents',
                'icon' => 'layers',
                'language' => 'tr'
            ],
            [
                'item_id' => $item5->id,
                'title' => 'Contents',
                'url' => 'admin/contents',
                'icon' => 'layers',
                'language' => 'en'
            ]
        ]);
        /* /Admin CMS Menus */
    }
}
