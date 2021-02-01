<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\VoteRequest;
use App\Models\Admin\Content\Content;
use App\Models\Comment;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Support\Facades\Validator;

class WebController extends Controller
{
    public function index()
    {
        $categories = Content::findSubContentsWithChildrenCountByLocale(config('site.categories'), ['contents.id', 'title', 'url', 'featured_image'], 8);
        $categoryIds = $categories->pluck('id')->toArray();
        $data = [
            'homeTop' => Content::findOneByLocale(config('site.home_top'), 'title', 'featured_image'),
            'footer' => Content::findOneByLocale(config('site.footer'), 'description', 'featured_image'),
            'category' => Content::findOneByLocale(config('site.categories'), 'title', 'url'),
            'categories' => $categories,
            'cards' => Content::findSubContentsByLocale(config('site.cards'), ['title', 'url', 'description', 'featured_image']),
            'userCount' => User::where('rank', config('user.rank.basic'))->count(),
            'parallax' => Content::findOneByLocale(config('site.home_parallax'), 'title', 'description', 'featured_image'),
            //'mostViewedContents' => Content::findMostViewedSubContents($categoryIds, ['title', 'url', 'featured_image', 'created_at'], 3)
        ];
        $data['featuredContents'] = Content::findSubContentsByLocale($categoryIds, ['title', 'url', 'description', 'featured_image', 'created_at', 'created_by_name']);
        $data['categoryCount'] = $data['categories']->count();
        // Sum of sub contents of categories
        $data['categoryItemChildrenSum'] = $categories->sum('childrens_count');

        return view('site.index', $data);
    }

    public function resolve(string $url)
    {
        $content = Content::findOneByLocaleWithUrl($url, 'contents.id', 'title', 'url', 'description', 'full', 'featured_image', 'created_at', 'created_by_name', 'meta_title', 'meta_description', 'meta_tags');
        if (!$content){
            return back();
        }
        $contentId = $content->id ?? 0;

        $data['_meta'] = [
            'title' => $content->meta_title,
            'description' => $content->meta_description,
            'keywords' => $content->meta_tags
        ];
        $data['parentTree'] = Content::parentTree($contentId, ['contents.id', 'title', 'url']); // Find parent tree for breadcrumb navigation

        // If given url has sub contents then return list view, if not return detail view
        if (Content::hasSubContents($contentId)){
            $data['category'] = $content;

            if ($contentId === config('site.categories')){
                $data['categories'] = Content::findSubContentsWithChildrenCountByLocale(config('site.categories'), ['contents.id', 'title', 'url', 'featured_image']);
                return view('site.category-list', $data);
            }else{
                $data['contents'] = Content::findSubContentsByLocaleInstance($contentId, ['title', 'url', 'description', 'featured_image', 'created_at', 'created_by_name'])->paginate(6);
                $data['mostViewedContents'] = Content::findMostViewedSubContents($contentId, ['title', 'url', 'featured_image', 'created_at'], 3);
                return view('site.content-list', $data);
            }
        }else{
            // Check if user already viewed, if not increment views by 1
            if (!session('viewed-' . $contentId)){
                $content->increment('views');
                session(['viewed-' . $contentId => true]);
            }

            $data['content'] = $content;
            $data['relationalContents'] = Content::findRelationalContentsByLocale($contentId, ['title', 'url', 'featured_image', 'created_at']);
            $comments = Comment::select('comments.id', 'comment', 'name', 'name_code', 'parent_id', 'comments.created_at', 'comments.user_id')->where('content_id', $contentId)->leftJoin('users', 'users.id', 'comments.user_id')->get();
            $data['vote'] = Vote::where('content_id', $contentId)->sum('vote');
            $data['comments'] = buildTree($comments, ['parentId' => 'parent_id']);
            $data['commentCount'] = $comments->count();
            return view('site.detail', $data);
        }
    }

    public function contentList()
    {
        $categories = Content::findSubContentsWithChildrenCountByLocale(config('site.categories'), ['contents.id']);
        $categoryIds = $categories->pluck('id')->toArray();
        $data['contents'] = Content::findSubContentsByLocaleInstance($categoryIds, ['title', 'url', 'description', 'featured_image', 'created_at', 'created_by_name'])->paginate(6);
        $data['mostViewedContents'] = Content::findMostViewedSubContents($categoryIds, ['title', 'url', 'featured_image', 'created_at'], 3);
        $data['parentTree'] = [];

        return view('site.content-list', $data);
    }

    public function search()
    {
        $q = request('q', '');
        $c = request('c', 0);
        $data = [
            'category' => Content::findOneByLocale($c, 'title', 'url'),
            'contents' => Content::findSubContentsByLocaleInstance($c, ['title', 'url', 'description', 'featured_image', 'created_at', 'created_by_name'])->where('title', 'like', '%'.$q.'%')->paginate(15),
            'search' => $q,
            '_meta' => [
                'title' => $q
            ]
        ];
        return view('site.search-list', $data);
    }

    public function searchTag($tag)
    {
        $data = [
            // TODO: mysql'e geçince değiştir (find_in_set sqlite ile çalışmıyor)
            //'contents' => Content::findByLocaleInstance('title', 'url', 'description', 'featured_image', 'created_at', 'created_by_name')->whereRaw('FIND_IN_SET(?, meta_tags)', [$tag])->paginate(15),
            'contents' => Content::findByLocaleInstance('title', 'url', 'description', 'featured_image', 'created_at', 'created_by_name')->where('meta_tags', 'like', '%'.$tag.'%')->paginate(15),
            'search' => $tag,
            '_meta' => [
                'title' => $tag
            ]
        ];
        return view('site.search-list', $data);
    }

    public function comment(CommentRequest $request)
    {
        $data = $request->validated();
        $parentId = $data['parent_id'] ?? 0;
        if ($parentId){
            // If user tries to reply to inner comment, prevent it.
            $parentComment = Comment::select('parent_id')->find($parentId); // Find the parent comment that user tries to reply
            if (intval($parentComment->parent_id) !== 0){ // If that parent comment has another parent prevent it
                return resJson(0);
            }
        }
        $data['user_id'] = auth()->id();
        return resJson(Comment::create($data));
    }

    public function vote(VoteRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        // If user voted this content already
        if(Vote::where('content_id', $data['content_id'])->where('user_id', $data['user_id'])->exists()){
            return resJson(0, ['message' => __('site.vote.already_voted')]);
        }


        return resJson(Vote::create($data));
    }
}
