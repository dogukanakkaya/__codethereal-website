<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommandSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('commands')->insert([
            [
                'title' => 'Clear Cache',
                'command' => 'ce:clear'
            ],
            [
                'title' => 'Clear Cache/Cache',
                'command' => 'ce:clear --cache'
            ],
            [
                'title' => 'Down',
                'command' => 'down'
            ],
            [
                'title' => 'Up',
                'command' => 'up'
            ]
        ]);
    }
}
