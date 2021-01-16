<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ContentRequest;
use App\Models\Admin\Content;
use App\Models\Admin\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class ContentController extends Controller
{
    public function index()
    {
        if (!Auth::user()->can('see_contents')) {
            return back();
        }
        $data = [
            'navigations' => [__('contents.self_plural')],
            'columns' => [
                ['data' => 'file', 'name' => 'file', 'title' => __('contents.photo'), 'orderable' => false, 'searchable' => false],
                ['data' => 'title', 'name' => 'title', 'title' => __('contents.title')],
                ['data' => 'status', 'name' => 'status', 'title' => __('contents.status'), 'searchable' => false],
                ['data' => 'parent', 'name' => 'parent', 'title' => __('contents.parent'), 'searchable' => false],
                ['data' => 'created_at', 'name' => 'created_at', 'title' => __('contents.created_at'), 'searchable' => false],
                ['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable' => false, 'className' => 'dt-actions'],
            ],
            'parents' => Content::findAllByLocale('contents.id', 'title', 'created_at')->pluck('title', 'id')->toArray()
        ];
        return view('admin.contents.index', $data);
    }

    public function datatable()
    {
        if (!Auth::user()->can('see_contents')) {
            return resJsonUnauthorized();
        }
        $data = Content::findAllByLocale('contents.id', 'title', 'active', 'created_at');
        return Datatables::of($data)
            ->addColumn('file', function (Content $content) {
                $file = Content::findFile($content->id);
                return isset($file->path) ? '<img src="' . asset('storage/' . $file->path) . '" class="table-img" alt="profile"/>' : '<div class="table-img"></div>';
            })
            ->editColumn('title', fn (Content $content) => '<a class="clickable" title="' . $content->id . '" onclick="__find(' . $content->id . ')">' . $content->title . '</a>')
            ->addColumn('action', fn (Content $content) => view('admin.partials.dropdown', ['actions' => $this->actions($content->id)]))
            ->addColumn('status', fn (Content $content) => statusBadge($content->active))
            ->addColumn('parent', fn (Content $content) => implode(', ', Content::findParents($content->id, 'title')->pluck('title')->toArray()))
            ->editColumn('created_at', fn (Content $content) => date("Y-m-d H:i:s", strtotime($content->created_at)))
            ->rawColumns(['file', 'title', 'status', 'action'])
            ->make(true);
    }

    public function create(ContentRequest $request)
    {
        if (!Auth::user()->can('create_contents')) {
            return resJsonUnauthorized();
        }
        $data = $request->validated();
        $contentData = array_remove($data, 'content');

        // Get and unset files and parents from content data
        $files = array_remove($contentData, 'files');
        $fileIds = $files !== '0' ? explode('|', $files) : [];

        $parentIds = array_remove($contentData, 'parents');

        DB::beginTransaction();
        try {
            // Create Content
            $content = Content::create($contentData);

            // Create Content Parents
            $parentsData = [];
            foreach ($parentIds as $parentId) {
                $parentsData[] = [
                    'content_id' => $content->id,
                    'parent_id' => $parentId
                ];
            }
            DB::table('content_parents')->insert($parentsData);

            // Create Content Files
            $filesData = [];
            foreach ($fileIds as $fileId) {
                $filesData[] = [
                    'content_id' => $content->id,
                    'file_id' => $fileId
                ];
            }
            DB::table('content_files')->insert($filesData);

            // Find content featured image to save database
            $contentFeaturedImage = Content::findFile($content->id);

            // Create Content Languages
            $translationData = [];
            foreach ($data as $language => $values) {
                $translationData[] = array_merge($values, [
                    'language' => $language,
                    'content_id' => $content->id,
                    'url' => Str::slug($values['title']),
                    'featured_image' => $contentFeaturedImage->path ?? ''
                ]);
            }
            DB::table('content_translations')->insert($translationData);

            DB::commit();
            return resJson(true);
        } catch (\Exception) {
            DB::rollBack();
            return resJson(false);
        }
    }

    public function destroy(int $id)
    {
        if (!Auth::user()->can('delete_contents')) {
            return resJsonUnauthorized();
        }
        return resJson(Content::destroy($id));
    }

    public function restore(int $id)
    {
        if (!Auth::user()->can('delete_contents')) {
            return resJsonUnauthorized();
        }
        return resJson(Content::withTrashed()->find($id)->restore());
    }

    public function find(int $id)
    {
        if (!Auth::user()->can('see_contents')) {
            return resJsonUnauthorized();
        }
        // TODO: We'll check that for better way for multi language operations (without model relations)
        $content = Content::select('searchable')->find($id);

        $translations = DB::table('content_translations')
            ->select('title', 'description', 'full', 'active', 'language')
            ->where('content_id', $id)
            ->get()
            ->keyBy('language')
            ->transform(function ($i) {
                // Remove language keys, i needed it only to make a keyBy on collection
                unset($i->language);
                return $i;
            });

        $files = Content::findFiles($id,'path', 'file_id')->pluck('path', 'file_id');

        $parents = Content::findParents($id, 'parent_id')->pluck('parent_id')->toArray();

        return response()->json([
            'content' => $content,
            'translations' => $translations,
            'files' => $files,
            'parents' => $parents
        ]);
    }

    public function update(ContentRequest $request, int $id)
    {
        if (!Auth::user()->can('update_contents')) {
            return resJsonUnauthorized();
        }
        $data = $request->validated();
        $contentData = array_remove($data, 'content');

        // Get and unset files and parents from content data
        $files = array_remove($contentData, 'files');
        $fileIds = $files !== '0' ? explode('|', $files) : [];

        $parentIds = array_remove($contentData, 'parents');

        DB::beginTransaction();
        try {
            // Update Content
            Content::where('id', $id)->update($contentData);

            // Update Content Parents
            DB::table('content_parents')->where('content_id', $id)->delete();
            $parentsData = [];
            foreach ($parentIds as $parentId) {
                $parentsData[] = [
                    'content_id' => $id,
                    'parent_id' => $parentId
                ];
            }
            DB::table('content_parents')->insert($parentsData);

            // Update Content Files
            DB::table('content_files')->where('content_id', $id)->delete();
            $filesData = [];
            foreach ($fileIds as $fileId) {
                $filesData[] = [
                    'content_id' => $id,
                    'file_id' => $fileId
                ];
            }
            DB::table('content_files')->insert($filesData);

            // Find content featured image to save database
            $contentFeaturedImage = Content::findFile($id);

            // Update Content Languages
            foreach ($data as $language => $values) {
                $values['url'] = Str::slug($values['title']);
                $values['featured_image'] = $contentFeaturedImage->path ?? '';
                DB::table('content_translations')
                    ->where('content_id', $id)
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
        if (!Auth::user()->can('sort_contents')) {
            return resJsonUnauthorized();
        }
        $contents = Content::findAllByLocale('contents.id', 'sequence', 'title')->sortBy('sequence');
        $data = [
            'navigations' => [route('contents.index') => __('contents.self_plural') ,__('contents.sort')],
            'contents' => $contents
        ];
        return view('admin.contents.sort', $data);
    }

    public function saveSequence()
    {
        if (!Auth::user()->can('sort_contents')) {
            return resJsonUnauthorized();
        }
        $data = request()->all();

        DB::beginTransaction();
        try {
            foreach ($data as $key => $id) {
                // I write this with query builder for better performance, there could be a lot of data to be ordered.
                DB::update('UPDATE contents SET updated_at = ?, sequence = ? WHERE id = ?;', [
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
     * Return table actions
     *
     * @param int $id
     * @return \string[][]
     */
    private function actions(int $id): array
    {
        return [
            ['title' => '<i class="material-icons-outlined md-18">edit</i> ' . __('buttons.update'), 'onclick' => '__find(' . $id . ')'],
            ['title' => '<i class="material-icons-outlined md-18">delete</i> ' . __('buttons.delete'), 'onclick' => '__delete(' . $id . ')'],
        ];
    }
}
