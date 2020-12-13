<?php

namespace Database\Seeders;

use App\Models\Admin\Content;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Content::create([
            'id' => 1
        ]);
        DB::table('content_translations')->insert([
            [
                'content_id' => 1,
                'title' => 'Content 1 TR',
                'description' => 'Content 1 Description TR',
                'full' => '<b>Merhaba dünya</b>',
                'url' => 'content-1-tr',
                'language' => 'tr'
            ],
            [
                'content_id' => 1,
                'title' => 'Content 1 EN',
                'description' => 'Content 1 Description EN',
                'full' => '<b>Hello world</b>',
                'url' => 'content-1-en',
                'language' => 'en'
            ]
        ]);
    }
}
