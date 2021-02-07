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
        /*
        $userDev = User::create([
            'name' => 'Doğukan Akkaya',
            'name_code' => 'DA',
            'email' => 'doguakkaya27@gmail.com',
            'password' => Hash::make(''),
            'position' => 'Software Developer',
            'rank' => config('user.rank.dev')
        ]);
        $userDev->markEmailAsVerified();
        $userDev->assignRole('developer');
        */
    }
}
