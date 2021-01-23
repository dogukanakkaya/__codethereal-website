<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Admin\Content\Content;
use App\Models\User;

class WebController extends Controller
{
    public function index()
    {
        $categoryItems = Content::findSubContentsWithChildrenCountByLocale(config('site.categories'), ['contents.id', 'title', 'url', 'featured_image'], 8);
        $data = [
            'category' => Content::findOneByLocale(config('site.categories'), 'title', 'url'),
            'categoryItems' => $categoryItems,
            'cards' => Content::findSubContentsByLocale(config('site.cards'), ['title', 'url', 'description', 'featured_image']),
            'userCount' => User::where('rank', config('user.rank.basic'))->count(),
            'parallax' => Content::findOneByLocale(config('site.home_parallax'), 'title', 'description', 'featured_image')
        ];
        $data['featuredContents'] = Content::findSubContentsByLocale($categoryItems->pluck('id')->toArray(), ['title', 'url', 'description', 'featured_image', 'created_at', 'created_by_name']);
        $data['categoryCount'] = $data['categoryItems']->count();
        // Sum of sub contents of categories
        $data['categoryItemChildrenSum'] = $categoryItems->sum('childrens_count');

        return view('site.index', $data);
    }

    public function resolve(string $url)
    {
        $content = Content::findOneByLocaleWithUrl($url, 'contents.id');
        if (Content::hasSubContents($content->id)){
            $data = [
                'contents' => Content::findSubContentsByLocaleInstance($content->id, ['title', 'url', 'description', 'featured_image', 'created_at', 'created_by_name'])->paginate(6),
            ];
            return view('site.list', $data);
        }else{
            $data = [
                'content' => Content::findOneByLocaleWithUrl($url, 'title', 'url', 'description', 'featured_image', 'created_at', 'created_by_name'),
            ];
            return view('site.detail', $data);
        }
    }
}
