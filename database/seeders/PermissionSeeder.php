<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name' => 'developer'
        ]);
        Permission::create([
            'name' => 'see_settings',
            'group' => 'settings',
            'title' => 'See Settings'
        ]);
        Permission::create([
            'name' => 'update_settings',
            'group' => 'settings',
            'title' => 'Update Settings'
        ]);
    }
}
