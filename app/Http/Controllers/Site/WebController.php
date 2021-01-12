<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Admin\Content;
use App\Models\Admin\Menu\Group;

class WebController extends Controller
{
    public function index()
    {
        $data = [
            'popularCategories' => Content::findSubContentsByLocale(config('site.popular_categories'), 'contents.id', 'title', 'featured_image')
        ];
        return view('site.index', $data);
    }
}
