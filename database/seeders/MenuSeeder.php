<?php

namespace Database\Seeders;

use App\Models\Menu\Group;
use App\Models\Menu\Item;
use App\Models\Menu\ItemTranslation;
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
        Group::create([
            'title' => 'Header Menus'
        ]);
        Item::create([
            'group_id' => 1
        ]);
        Item::create([
            'group_id' => 1
        ]);
        DB::table('menu_item_translations')->insert([
            [
                'item_id' => 1,
                'title' => 'Menu 1 TR',
                'url' => 'menu-1-tr',
                'language' => 'tr'
            ],
            [
                'item_id' => 1,
                'title' => 'Menu 1 EN',
                'url' => 'menu-1-en',
                'language' => 'en'
            ],
            [
                'item_id' => 2,
                'title' => 'Menu 2 TR',
                'url' => 'menu-2-tr',
                'language' => 'tr'
            ],
            [
                'item_id' => 2,
                'title' => 'Menu 2 EN',
                'url' => 'menu-2-en',
                'language' => 'en'
            ]
        ]);
    }
}
