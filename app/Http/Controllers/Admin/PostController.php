<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PostRequest;
use App\Models\Admin\Post\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class PostController extends Controller
{
    public function index()
    {
        if (!Auth::user()->can('see_posts')) {
            return back();
        }
        $data = [
            'navigations' => [__('posts.self_plural')],
            'columns' => $this->columns(),
            'posts' => Post::findAllByLocale('posts.id', 'title')->pluck('title', 'id')->toArray()
        ];
        return view('admin.posts.index', $data);
    }

    public function datatable()
    {
        if (!Auth::user()->can('see_posts')) {
            return resJsonUnauthorized();
        }
        $data = Post::findAllByLocale('posts.id', 'title', 'active', 'created_at');
        return Datatables::of($data)
            ->editColumn('title', fn (Post $post) => '<a class="clickable" title="' . $post->id . '" onclick="__find(' . $post->id . ')">' . $post->title . '</a>')
            ->editColumn('created_at', fn (Post $post) => date("Y-m-d H:i:s", strtotime($post->created_at)))
            ->addColumn('check_all', fn (Post $post) => '<input type="checkbox" onclick="__onCheck()" value="' . $post->id . '" name="checked[]"/>')
            ->addColumn('file', function (Post $post) {
                $file = Post::findFile($post->id);
                return isset($file->path) ? '<img src="' . asset('storage/' . $file->path) . '" class="table-img" alt="profile"/>' : '<div class="table-img"></div>';
            })
            //->addColumn('action', fn (Content $content) => view('admin.partials.dropdown', ['actions' => $this->actions($content->id)]))
            ->addColumn('action', fn (Post $post) => view('admin.partials.single-actions', ['actions' => $this->actions($post->id)]))
            ->addColumn('status', fn (Post $post) => statusBadge($post->active))
            ->addColumn('parent', fn (Post $post) => implode(', ', Post::findParentsByLocale($post->id, 'title')->pluck('title')->toArray()))
            ->rawColumns(['check_all', 'file', 'title', 'status', 'action'])
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
        $postData['created_by_name'] = Auth::user()->name;
        $postData['updated_by'] = Auth::id();

        // Get and unset files and parents from post data
        $files = array_remove($postData, 'files');
        $fileIds = $files !== '0' && $files !== null ? explode('|', $files) : [];

        $parentIds = array_remove($postData, 'parents') ?? [];
        $relationsIds = array_remove($postData, 'relations') ?? [];

        DB::beginTransaction();
        try {
            // Create Post
            $post = Post::create($postData);

            // Create Post Parents
            DB::table('post_parents')->insert($this->prepareParentsData($post->id, $parentIds));

            // Create Post Relations
            DB::table('post_relations')->insert($this->prepareRelationsData($post->id, $relationsIds));

            // Create Post Files
            DB::table('post_files')->insert($this->prepareFilesData($post->id, $fileIds));

            // Find Post featured image to save database
            $featuredImage = Post::findFile($post->id);

            // Create Post Languages
            $translationData = [];
            foreach ($data as $language => $values) {
                // If same named record exists add -id suffix to the url
                $recordExists = DB::table('posts')
                    ->where('language', $language)
                    ->where('title', $values['title'])
                    ->whereNull('deleted_at')
                    ->leftJoin('post_translations', 'post_translations.post_id', 'posts.id')
                    ->exists();
                $appendId = ($recordExists) ? '-'.$post->id : '';

                $translationData[] = array_merge($values, [
                    'language' => $language,
                    'post_id' => $post->id,
                    'url' => Str::slug($values['title']) . $appendId,
                    'categories' => implode(', ', Post::findByLocaleInstance('title')->whereIn('posts.id', $parentIds)->get()->pluck('title')->toArray()),
                    'featured_image' => $featuredImage->path ?? '',
                ]);
            }
            DB::table('post_translations')->insert($translationData);

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
        return resJson(Post::destroy(explode(',', $id)));
    }

    public function restore(int|string $id)
    {
        if (!Auth::user()->can('delete_posts')) {
            return resJsonUnauthorized();
        }
        return resJson(Post::withTrashed()->whereIn('id', explode(',', $id))->restore());
    }

    public function find(int $id)
    {
        if (!Auth::user()->can('see_posts')) {
            return resJsonUnauthorized();
        }
        // TODO: We'll check that for better way for multi language operations (without model relations)
        $post = Post::select('searchable')->find($id);

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

        $files = Post::findFiles($id,'path', 'file_id')->pluck('path', 'file_id');
        $parents = Post::findParentsByLocale($id, 'parent_id')->pluck('parent_id')->toArray();
        $relations = Post::findRelationsByLocale($id, 'relation_id')->pluck('relation_id')->toArray();

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

        $parentIds = array_remove($postData, 'parents') ?? [];
        $relationIds = array_remove($postData, 'relations') ?? [];

        DB::beginTransaction();
        try {
            // Update Post
            Post::where('id', $id)->update($postData);

            // Update Post Parents
            DB::table('post_parents')->where('post_id', $id)->delete();
            DB::table('post_parents')->insert($this->prepareParentsData($id, $parentIds));

            // Update Post Relations
            DB::table('post_relations')->where('post_id', $id)->delete();
            DB::table('post_relations')->insert($this->prepareRelationsData($id, $relationIds));

            // Update Post Files
            DB::table('post_files')->where('post_id', $id)->delete();
            DB::table('post_files')->insert($this->prepareFilesData($id, $fileIds));

            // Find Post featured image to save database
            $featuredImage = Post::findFile($id);

            // Update Post Languages
            foreach ($data as $language => $values) {
                // If same named record exists add -id suffix to the url
                $recordExists = DB::table('posts')
                    ->where('posts.id', '!=', $id)
                    ->where('language', $language)
                    ->where('title', $values['title'])
                    ->whereNull('deleted_at')
                    ->leftJoin('post_translations', 'post_translations.post_id', 'posts.id')
                    ->exists();
                $appendId = ($recordExists) ? '-'.$id : '';

                $values['url'] = Str::slug($values['title']) . $appendId;
                $values['featured_image'] = $featuredImage->path ?? '';
                $values['categories'] = implode(', ', Post::findByLocaleInstance('title')->whereIn('posts.id', $parentIds)->get()->pluck('title')->toArray());
                DB::table('post_translations')
                    ->where('post_id', $id)
                    ->where('language', $language)
                    ->update($values);
            }

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
            'posts' => Post::findAllByLocale('posts.id', 'sequence', 'title')->sortBy('sequence')
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
            DB::commit();
            return resJson(true);
        } catch (\Exception) {
            DB::rollBack();
            return resJson(false);
        }
    }

    /**
     * Collect file ids in 1 array and return it
     *
     * @param int $postId
     * @param array $fileIds
     * @return array
     */
    private function prepareFilesData(int $postId, array $fileIds): array
    {
        $filesData = [];
        foreach ($fileIds as $fileId) {
            $filesData[] = [
                'post_id' => $postId,
                'file_id' => $fileId
            ];
        }
        return $filesData;
    }

    /**
     * Collect parent ids in 1 array and return it
     *
     * @param int $postId
     * @param array $parentIds
     * @return array
     */
    private function prepareParentsData(int $postId, array $parentIds): array
    {
        $parentsData = [];
        foreach ($parentIds as $parentId) {
            if (empty($parentId)) continue;
            $parentsData[] = [
                'post_id' => $postId,
                'parent_id' => $parentId
            ];
        }
        return $parentsData;
    }

    /**
     * Collect parent ids in 1 array and return it
     *
     * @param int $postId
     * @param array $relationIds
     * @return array
     */
    private function prepareRelationsData(int $postId, array $relationIds): array
    {
        $relationsData = [];
        foreach ($relationIds as $relationId) {
            if (empty($relationId)) continue;
            $relationsData[] = [
                'post_id' => $postId,
                'relation_id' => $relationId
            ];
        }
        return $relationsData;
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
            ['data' => 'created_at', 'name' => 'created_at', 'title' => __('posts.created_at'), 'searchable' => false],
            ['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable' => false, 'className' => 'dt-actions'],
        ];
    }
}
