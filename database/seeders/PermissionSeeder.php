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

        /* Settings */
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
        /* /Settings */

        /* Users */
        Permission::create([
            'name' => 'see_users',
            'group' => 'users',
            'title' => 'See Users'
        ]);
        Permission::create([
            'name' => 'create_users',
            'group' => 'users',
            'title' => 'Create Users'
        ]);
        Permission::create([
            'name' => 'update_users',
            'group' => 'users',
            'title' => 'Update Users'
        ]);
        Permission::create([
            'name' => 'delete_users',
            'group' => 'users',
            'title' => 'Delete Users'
        ]);
        /* /Users */
    }
}
