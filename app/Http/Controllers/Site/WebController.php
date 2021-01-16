<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Admin\Content\Content;
use App\Models\User;

class WebController extends Controller
{
    public function index()
    {
        $data = [
            'category' => Content::findOneByLocale(config('site.categories'), 'title', 'url'),
            'categoryItems' => Content::findSubContentsWithChildrenCountByLocale(config('site.categories'), 8, ['title', 'url', 'featured_image']),
            'cards' => Content::findSubContentsByLocale(config('site.cards'), ['title', 'url', 'description', 'featured_image']),
            'userCount' => User::where('rank', config('user.rank.basic'))->count()
        ];
        $data['categoryCount'] = $data['categoryItems']->count();

        // Sum of sub contents of categories
        $data['categoryItemChildrenSum'] = $data['categoryItems']->sum('childrens_count');

        return view('site.index', $data);
    }
}
