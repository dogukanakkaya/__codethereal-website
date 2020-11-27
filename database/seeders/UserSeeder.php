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
        $user = User::create([
            'name' => 'Doğukan Akkaya',
            'email' => 'doguakkaya27@gmail.com',
            'password' => Hash::make('12345678'),
            'position' => 'Software Developer',
            'rank' => config('user.rank.dev')
        ]);
        $user->markEmailAsVerified();
        $user->assignRole('developer');

        $userAdmin = User::create([
            'name' => 'Doğu Admin',
            'email' => 'doguakkaya27@hotmail.com',
            'password' => Hash::make('12345678'),
            'position' => '',
            'rank' => config('user.rank.admin')
        ]);
        $userAdmin->markEmailAsVerified();
    }
}
