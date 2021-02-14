<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SitemapGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a sitemap';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $categories = DB::table('posts')
            ->select('title', 'url', 'updated_at')
            ->where('post_parents.parent_id', config('site.categories'))
            ->whereNull('deleted_at')
            ->leftJoin('post_translations', 'post_translations.post_id', 'posts.id')
            ->leftJoin('post_parents', 'post_parents.post_id', 'posts.id')
            ->get();

        $posts = DB::table('posts')
            ->select('title', 'url', 'updated_at')
            ->whereNull('deleted_at')
            ->where('searchable', 1)
            ->leftJoin('post_translations', 'post_translations.post_id', 'posts.id')
            ->get();

        $xml = '';
        foreach ($categories as $category) {
            $xml .= '
                <url>
                    <loc>'.createUrl($category->url).'</loc>
                    <lastmod>'.date("Y-m-d\TH:i:sP", strtotime($category->updated_at)).'</lastmod>
                    <priority>0.8</priority>
                </url>
            ';
        }

        foreach ($posts as $post) {
            $xml .= '
                <url>
                    <loc>'.createUrl($post->url).'</loc>
                    <lastmod>'.date("Y-m-d\TH:i:sP", strtotime($post->updated_at)).'</lastmod>
                    <priority>0.9</priority>
                </url>
            ';
        }

        $fullXml = '<?xml version="1.0" encoding="UTF-8"?>
            <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
                <url>
                    <loc>'.url('').'</loc>
                    <lastmod>'.date("Y-m-d").'</lastmod>
                    <priority>1.0</priority>
                </url>
                '.$xml.'
            </urlset>
        ';

        file_put_contents('sitemap.xml', $fullXml);

        return 0;
    }
}
