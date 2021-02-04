<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\ContactRequest;
use App\Http\Requests\VoteRequest;
use App\Mail\ContactMail;
use App\Models\Admin\Post\Post;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class WebController extends Controller
{
    public function index()
    {
        $categories = Post::findSubPostsWithChildrenCountByLocale(config('site.categories'), ['posts.id', 'title', 'url', 'featured_image'], 8);
        $categoryIds = $categories->pluck('id')->toArray();
        $data = [
            'homeTop' => Post::findOneByLocale(config('site.home_top'), 'title', 'featured_image'),
            'category' => Post::findOneByLocale(config('site.categories'), 'title', 'url'),
            'categories' => $categories,
            'cards' => Post::findSubPostsByLocale(config('site.cards'), ['title', 'url', 'description', 'featured_image']),
            'userCount' => User::where('rank', config('user.rank.basic'))->count(),
            'parallax' => Post::findOneByLocale(config('site.home_parallax'), 'title', 'description', 'featured_image')
        ];
        $data['featuredPosts'] = Post::findSubPostsByLocale($categoryIds, ['title', 'url', 'description', 'featured_image', 'created_at', 'created_by_name']);
        $data['categoryCount'] = $data['categories']->count();
        // Sum of sub posts of categories
        $data['categoryItemChildrenSum'] = $categories->sum('childrens_count');

        return view('site.index', $data);
    }

    /**
     * Resolve any url (list page, detail page, category page)
     *
     * @param string $url
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function resolve(string $url)
    {
        $post = Post::findOneByLocaleWithUrl($url, 'posts.id', 'title', 'url', 'description', 'full', 'featured_image', 'created_at', 'created_by_name', 'meta_title', 'meta_description', 'meta_tags');
        if (!$post){
            return back();
        }
        $postId = $post->id ?? 0;

        $data['_meta'] = [
            'title' => $post->meta_title,
            'description' => $post->meta_description,
            'keywords' => $post->meta_tags
        ];
        $data['parentTree'] = Post::parentTree($postId, ['posts.id', 'title', 'url']); // Find parent tree for breadcrumb navigation

        // If given url has sub posts then return list view, if not return detail view
        if (Post::hasSubPosts($postId)){
            $data['category'] = $post;

            if ($postId === config('site.categories')){
                $data['categories'] = Post::findSubPostsWithChildrenCountByLocale(config('site.categories'), ['posts.id', 'title', 'url', 'featured_image']);
                return view('site.category-list', $data);
            }else{
                $data['posts'] = Post::findSubPostsByLocaleInstance($postId, ['title', 'url', 'description', 'featured_image', 'created_at', 'created_by_name'])->paginate(6);
                $data['mostViewedPosts'] = Post::findMostViewedSubPosts($postId, ['title', 'url', 'featured_image', 'created_at'], 3);
                return view('site.post-list', $data);
            }
        }else{
            // Check if user already viewed, if not increment views by 1
            if (!session('viewed-' . $postId)){
                $post->increment('views');
                session(['viewed-' . $postId => true]);
            }

            $data['post'] = $post;
            $data['relationalPosts'] = Post::findRelationalPostsByLocale($postId, ['title', 'url', 'featured_image', 'created_at']);
            $comments = Comment::select('comments.id', 'comment', 'name', 'name_code', 'parent_id', 'comments.created_at', 'comments.user_id')->where('post_id', $postId)->leftJoin('users', 'users.id', 'comments.user_id')->get();
            $data['vote'] = DB::table('votes')->where('post_id', $postId)->sum('vote');
            $data['comments'] = buildTree($comments, ['parentId' => 'parent_id']);
            $data['commentCount'] = $comments->count();
            return view('site.detail', $data);
        }
    }

    /**
     * All posts page to list all of the posts
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function postList()
    {
        $categories = Post::findSubPostsWithChildrenCountByLocale(config('site.categories'), ['posts.id']);
        $categoryIds = $categories->pluck('id')->toArray();
        $data['posts'] = Post::findSubPostsByLocaleInstance($categoryIds, ['title', 'url', 'description', 'featured_image', 'created_at', 'created_by_name'])->paginate(6);
        $data['mostViewedPosts'] = Post::findMostViewedSubPosts($categoryIds, ['title', 'url', 'featured_image', 'created_at'], 3);
        $data['parentTree'] = [];

        return view('site.post-list', $data);
    }

    /**
     * Search and return items in json response
     *
     * @param string|null $q
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(string|null $q = null)
    {
        $searched = [];
        if ($q !== null){
            $searched = DB::table('posts')
                ->select('title', 'url')
                ->whereNull('deleted_at')
                ->where('language', app()->getLocale())
                ->where('searchable', 1)
                ->where(function($query) use($q) {
                    $query->where('title', 'like', '%'.$q.'%')
                        ->orWhere('meta_title', 'like', '%'.$q.'%')
                        ->orWhere('description', 'like', '%'.$q.'%')
                        ->orWhere('meta_description', 'like', '%'.$q.'%');
                })
                ->leftJoin('post_translations', 'post_translations.post_id', 'posts.id')
                ->take(30)
                ->get();
        }
        return response()->json(['items' => $searched]);
    }

    /**
     * Search a tag
     *
     * @param $tag
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function searchTag($tag)
    {
        $data = [
            // TODO: mysql'e geçince değiştir (find_in_set sqlite ile çalışmıyor)
            //'posts' => Post::findByLocaleInstance('title', 'url', 'description', 'featured_image', 'created_at', 'created_by_name')->whereRaw('FIND_IN_SET(?, meta_tags)', [$tag])->paginate(15),
            'posts' => Post::findByLocaleInstance('title', 'url', 'description', 'featured_image', 'created_at', 'created_by_name')->where('meta_tags', 'like', '%'.$tag.'%')->paginate(15),
            'search' => $tag,
            '_meta' => [
                'title' => $tag
            ]
        ];
        return view('site.search-list', $data);
    }

    /**
     * Send comment to a post
     *
     * @param CommentRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Vote a post
     *
     * @param VoteRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function vote(VoteRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        // If user voted this post already
        $postVotedAlready = DB::table('votes')->where('post_id', $data['post_id'])->where('user_id', $data['user_id'])->exists();
        if($postVotedAlready){
            return resJson(0, ['message' => __('site.vote.already_voted')]);
        }

        return resJson(DB::table('votes')->insert($data));
    }

    /**
     * Save a post
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function savePost()
    {
        $data = request()->only('post_id');
        $validator = Validator::make($data, [
            'post_id' => 'required|integer|exists:posts,id'
        ]);
        if ($validator->fails()){
            return resJson(0);
        }
        $data['user_id'] = auth()->id();

        // If user saved this post already
        $postSavedAlready = DB::table('saved_posts')->where('post_id', $data['post_id'])->where('user_id', $data['user_id'])->exists();
        if ($postSavedAlready){
            return resJson(0, ['message' => __('site.saved_posts.already_saved')]);
        }
        $data['created_at'] = now();

        return resJson(DB::table('saved_posts')->insert($data));
    }

    /**
     * Return contact view modal
     *
     * @return \Illuminate\Http\Response
     */
    public function contactView()
    {
        return response()
            ->view('site.partials.contact-modal')
            ->header('Content-Type', 'application/html');
    }

    /**
     * Send contact email to site manager
     *
     * @param ContactRequest $request
     */
    public function contact(ContactRequest $request)
    {
        Mail::to(env('APP_CONTACT', 'doguakkaya27@gmail.com'))->send(new ContactMail($request->validated()));
    }
}
