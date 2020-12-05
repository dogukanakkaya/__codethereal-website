<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            PermissionSeeder::class,
            UserSeeder::class,
            LanguageSeeder::class,
            MenuSeeder::class,
            ContentSeeder::class
        ]);
        // \App\Models\User::factory(10)->create();
    }
}
