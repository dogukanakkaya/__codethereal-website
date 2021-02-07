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
            'name' => 'Codethereal DEV',
            'name_code' => 'CD',
            'email' => 'dev@codethereal.com',
            'password' => Hash::make('12345678'),
            'position' => 'Software Developer',
            'rank' => config('user.rank.dev')
        ]);
        $userDev->markEmailAsVerified();
        $userDev->markAsAuthorized();
        $userDev->assignRole('developer');
    }
}
