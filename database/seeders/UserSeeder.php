<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $userDev = User::create([
            'name' => 'Doğukan Akkaya',
            'email' => 'doguakkaya27@gmail.com',
            'password' => Hash::make('12345678'),
            'position' => 'Software Developer',
            'rank' => config('user.rank.dev')
        ]);
        $userDev->markEmailAsVerified();
        $userDev->markAsAuthorized();
        $userDev->assignRole('developer');

        $userAdmin = User::create([
            'name' => 'Doğu Admin',
            'email' => 'doguakkaya27@hotmail.com',
            'password' => Hash::make('12345678'),
            'position' => '',
            'rank' => config('user.rank.admin')
        ]);
        $userAdmin->markEmailAsVerified();
        $userAdmin->markAsAuthorized();

        $userBasic = User::create([
            'name' => 'Doğu Basic',
            'email' => 'psymon775@gmail.com',
            'password' => Hash::make('12345678'),
            'position' => '',
            'rank' => config('user.rank.basic')
        ]);
        $userBasic->markEmailAsVerified();
        $userBasic->markAsAuthorized();
    }
}
