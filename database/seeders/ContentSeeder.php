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
                'url' => 'content-1-tr',
                'language' => 'tr'
            ],
            [
                'content_id' => 1,
                'title' => 'Content 1 EN',
                'url' => 'content-1-en',
                'language' => 'en'
            ]
        ]);
    }
}
