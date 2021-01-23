<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Admin\Content\Content;
use App\Models\User;

class WebController extends Controller
{
    public function index()
    {
        $categories = Content::findSubContentsWithChildrenCountByLocale(config('site.categories'), ['contents.id', 'title', 'url', 'featured_image'], 8);
        $data = [
            'category' => Content::findOneByLocale(config('site.categories'), 'title', 'url'),
            'categories' => $categories,
            'cards' => Content::findSubContentsByLocale(config('site.cards'), ['title', 'url', 'description', 'featured_image']),
            'userCount' => User::where('rank', config('user.rank.basic'))->count(),
            'parallax' => Content::findOneByLocale(config('site.home_parallax'), 'title', 'description', 'featured_image')
        ];
        $data['featuredContents'] = Content::findSubContentsByLocale($categories->pluck('id')->toArray(), ['title', 'url', 'description', 'featured_image', 'created_at', 'created_by_name']);
        $data['categoryCount'] = $data['categories']->count();
        // Sum of sub contents of categories
        $data['categoryItemChildrenSum'] = $categories->sum('childrens_count');

        return view('site.index', $data);
    }

    public function resolve(string $url)
    {
        $content = Content::findOneByLocaleWithUrl($url, 'contents.id', 'title', 'url', 'description', 'full', 'featured_image', 'created_at', 'created_by_name', 'meta_tags');
        $data['parentTree'] = Content::parentTree($content->id, ['contents.id', 'title', 'url']); // Find parent tree for breadcrumb navigation

        // If given url has sub contents then return list view, if not return detail view
        if (Content::hasSubContents($content->id)){
            $data['category'] = $content;

            if ($content->id === config('site.categories')){
                $data['categories'] = Content::findSubContentsWithChildrenCountByLocale(config('site.categories'), ['contents.id', 'title', 'url', 'featured_image']);
                return view('site.category-list', $data);
            }else{
                $data['contents'] = Content::findSubContentsByLocaleInstance($content->id, ['title', 'url', 'description', 'featured_image', 'created_at', 'created_by_name'])->paginate(6);
                $data['mostViewedContents'] = Content::findMostViewedSubContents($content->id, ['title', 'url', 'featured_image', 'created_at'], 3);
                return view('site.content-list', $data);
            }
        }else{
            // Check if user already viewed, if not increment views by 1
            if (!session('viewed-' . $content->id)){
                $content->increment('views');
                session(['viewed-' . $content->id => true]);
            }

            $data['content'] = $content;
            $data['relationalContents'] = Content::findRelationalContentsByLocale($content->id, ['title', 'url', 'featured_image', 'created_at']);
            return view('site.detail', $data);
        }
    }
}
