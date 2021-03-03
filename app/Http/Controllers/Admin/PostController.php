<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PostRequest;
use App\Models\Post\Post;
use App\Repositories\Interfaces\PostRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PostController extends Controller
{
    public function __construct(private PostRepositoryInterface $postRepository){}

    public function index()
    {
        if (!Auth::user()->can('see_posts')) {
            return back();
        }
        $data = [
            'navigations' => [__('posts.self_plural')],
            'columns' => $this->columns(),
            'posts' => $this->postRepository->selectables()
        ];
        return view('admin.posts.index', $data);
    }

    public function datatable()
    {
        if (!Auth::user()->can('see_posts')) {
            return resJsonUnauthorized();
        }
        $data = Post::select('posts.id', 'title', 'active', 'views', 'created_at')
            ->where('language', app()->getLocale())
            ->leftJoin('post_translations', 'post_translations.post_id', 'posts.id')
            ->latest()
            ->get();

        return Datatables::of($data)
            ->editColumn('title', fn (Post $post) => '<a class="clickable" title="' . $post->id . '" onclick="__find(' . $post->id . ')">' . $post->title . '</a>')
            ->editColumn('created_at', fn (Post $post) => date("Y-m-d H:i:s", strtotime($post->created_at)))
            ->editColumn('views', fn (Post $post) => '<i class="material-icons-outlined md-18">remove_red_eye</i> ' . $post->views)
            ->addColumn('check_all', fn (Post $post) => '<input type="checkbox" onclick="__onCheck()" value="' . $post->id . '" name="checked[]"/>')
            ->addColumn('file', function (Post $post) {
                $file = $this->postRepository->firstFile($post->id);
                return isset($file->path) ? '<img src="' . asset('storage/' . $file->path) . '" class="table-img" alt="profile"/>' : '<div class="table-img"></div>';
            })
            //->addColumn('action', fn (Content $content) => view('admin.partials.dropdown', ['actions' => $this->actions($content->id)]))
            ->addColumn('action', fn (Post $post) => view('admin.partials.single-actions', ['actions' => $this->actions($post->id)]))
            ->addColumn('status', fn (Post $post) => statusBadge($post->active))
            ->addColumn('parent', fn (Post $post) => implode(', ', $this->postRepository->parents($post->id, ['title'])->pluck('title')->toArray()))
            ->rawColumns(['check_all', 'file', 'title', 'status', 'views', 'action'])
            ->make(true);
    }

    public function create(PostRequest $request)
    {
        if (!Auth::user()->can('create_posts')) {
            return resJsonUnauthorized();
        }
        $data = $request->validated();
        $postData = array_remove($data, 'post');
        $postData['created_by'] = Auth::id();
        $postData['created_by_name'] = Auth::user()->isDev() ? config('app.name') : Auth::user()->name;
        $postData['updated_by'] = Auth::id();

        // Get and unset files and parents from post data
        $files = array_remove($postData, 'files');
        $fileIds = $files !== '0' ? explode('|', $files) : [];

        $parentIds = array_remove($postData, 'parents');
        $relationIds = array_remove($postData, 'relations');

        DB::beginTransaction();
        try {
            // Create Post
            $post = Post::create($postData);

            // Create Post Parents
            $this->postRepository->insertParents($post->id, $parentIds);

            // Create Post Relations
            $this->postRepository->insertRelations($post->id, $relationIds);

            // Create Post Files
            $this->postRepository->insertFiles($post->id, $fileIds);

            // Create Post Translations
            $this->postRepository->insertTranslations($post->id, $data);

            // Clear cache for some
            cache()->forget('home-posts');
            cache()->forget('home-articles');
            cache()->forget('home-categories');

            DB::commit();
            return resJson(true);
        } catch (\Exception) {
            DB::rollBack();
            return resJson(false);
        }
    }

    public function destroy(int|string $id)
    {
        if (!Auth::user()->can('delete_posts')) {
            return resJsonUnauthorized();
        }
        // Remove the posts cache
        cache()->forget('home-posts');

        return resJson(Post::destroy(explode(',', $id)));
    }

    public function restore(int|string $id)
    {
        if (!Auth::user()->can('delete_posts')) {
            return resJsonUnauthorized();
        }
        // Remove the home posts and home categories cache
        cache()->forget('home-posts');
        cache()->forget('home-categories');

        return resJson(Post::withTrashed()->whereIn('id', explode(',', $id))->restore());
    }

    public function find(int $id)
    {
        if (!Auth::user()->can('see_posts')) {
            return resJsonUnauthorized();
        }
        // TODO: We'll check that for better way for multi language operations (without model relations)
        $post = Post::find($id);

        $translations = DB::table('post_translations')
            ->select('title', 'description', 'full', 'active', 'meta_title', 'meta_description', 'meta_tags', 'language')
            ->where('post_id', $id)
            ->get()
            ->keyBy('language')
            ->transform(function ($i) {
                // Remove language keys, i needed it only to make a keyBy on collection
                unset($i->language);
                return $i;
            });

        $files = $this->postRepository->files($id, ['path', 'file_id'])->pluck('path', 'file_id');
        $parents = $this->postRepository->parents($id, ['parent_id'])->pluck('parent_id')->toArray();
        $relations = $this->postRepository->relations($id, ['relation_id'])->pluck('relation_id')->toArray();

        return response()->json([
            'post' => $post,
            'translations' => $translations,
            'files' => $files,
            'parents' => $parents,
            'relations' => $relations
        ]);
    }

    public function update(PostRequest $request, int $id)
    {
        if (!Auth::user()->can('update_posts')) {
            return resJsonUnauthorized();
        }
        $data = $request->validated();
        $postData = array_remove($data, 'post');
        $postData['updated_by'] = Auth::id();

        // Get and unset files and parents from post data
        $files = array_remove($postData, 'files');
        $fileIds = $files !== '0' && $files !== null ? explode('|', $files) : [];

        $parentIds = array_remove($postData, 'parents');
        $relationIds = array_remove($postData, 'relations');

        DB::beginTransaction();
        try {
            // Update Post
            Post::where('id', $id)->update($postData);

            // Update Post Parents
            $this->postRepository->updatePostParents($id, $parentIds);

            // Update Post Relations
            $this->postRepository->updatePostRelations($id, $relationIds);

            // Update Post Files
            $this->postRepository->updatePostFiles($id, $fileIds);

            // Update Post Translations
            $this->postRepository->updateTranslations($id, $data);

            // Clear cache for some
            cache()->forget('home-posts');
            cache()->forget('home-articles');
            cache()->forget('home-categories');

            DB::commit();
            return resJson(true);
        } catch (\Exception) {
            DB::rollBack();
            return resJson(false);
        }
    }

    public function sort()
    {
        if (!Auth::user()->can('sort_posts')) {
            return resJsonUnauthorized();
        }
        $data = [
            'navigations' => [route('posts.index') => __('posts.self_plural') ,__('posts.sort')],
            'posts' => $this->postRepository->all(['posts.id', 'sequence', 'title'])->sortBy('sequence')
        ];
        return view('admin.posts.sort', $data);
    }

    public function saveSequence()
    {
        if (!Auth::user()->can('sort_posts')) {
            return resJsonUnauthorized();
        }
        $data = request()->all();

        DB::beginTransaction();
        try {
            foreach ($data as $key => $id) {
                // I write this with query builder for better performance, there could be a lot of data to be ordered.
                DB::update('UPDATE posts SET updated_by = ?, updated_at = ?, sequence = ? WHERE id = ?;', [
                    Auth::id(),
                    now(),
                    $key,
                    $id
                ]);
            }
            // Remove the home posts and home categories cache
            cache()->forget('home-posts');
            cache()->forget('home-categories');

            DB::commit();
            return resJson(true);
        } catch (\Exception) {
            DB::rollBack();
            return resJson(false);
        }
    }

    /**
     * Return table actions
     *
     * @param int $id
     * @return \string[][]
     */
    private function actions(int $id): array
    {
        /*
        return [
            ['title' => '<i class="material-icons-outlined md-18">edit</i> ' . __('buttons.update'), 'onclick' => '__find(' . $id . ')'],
            ['title' => '<i class="material-icons-outlined md-18">delete</i> ' . __('buttons.delete'), 'onclick' => '__delete(' . $id . ')'],
        ];
        */
        return  [
            '<button class="btn btn-info text-white btn-sm" onclick="__find(' . $id . ')"><i class="material-icons-outlined md-18">edit</i></button>',
            '<button class="btn btn-danger text-white btn-sm" onclick="__delete(' . $id . ')"><i class="material-icons-outlined md-18">delete</i></button>'
        ];
    }

    /**
     * Return the table columns
     *
     * @return array[]
     */
    private function columns(): array
    {
        return [
            ['data' => 'file', 'name' => 'file', 'title' => __('posts.photo'), 'orderable' => false, 'searchable' => false],
            ['data' => 'title', 'name' => 'title', 'title' => __('posts.title')],
            ['data' => 'status', 'name' => 'status', 'title' => __('posts.status'), 'searchable' => false],
            ['data' => 'parent', 'name' => 'parent', 'title' => __('posts.parents'), 'searchable' => false],
            ['data' => 'views', 'name' => 'views', 'title' => __('posts.views'), 'searchable' => false],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => __('posts.created_at'), 'searchable' => false],
            ['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable' => false, 'className' => 'dt-actions'],
        ];
    }
}
