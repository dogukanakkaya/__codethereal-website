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
        $data = Content::findAllByLocale('contents.id', 'title', 'parent_id', 'active', 'created_at');
        return Datatables::of($data)
            ->addColumn('file', function (Content $content) {
                $file = Content::findContentFile($content->id);
                return isset($file->path) ? '<img src="' . asset('storage/' . $file->path) . '" class="table-img" alt="profile"/>' : '<div class="table-img"></div>';
            })
            ->editColumn('title', function (Content $content) {
                return '<a class="clickable" title="' . $content->id . '" onclick="__find(' . $content->id . ')">' . $content->title . '</a>';
            })
            ->addColumn('action', function (Content $content) {
                return view('admin.partials.dropdown', ['actions' => $this->actions($content->id)]);
            })
            ->addColumn('status', function (Content $content) {
                return $content->active == 1 ? '<span class="badge bg-success"><i class="material-icons-outlined md-18">check</i></span>' : '<span class="badge bg-danger"><i class="material-icons-outlined md-18">close</i></span></span>';
            })
            ->addColumn('parent', function (Content $content) {
                return Content::findOneByLocale($content->parent_id, 'title')->title ?? '';
            })
            ->editColumn('created_at', function (Content $content) {
                return date("Y-m-d H:i:s", strtotime($content->created_at));
            })
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

        // Get and unset files from content data and if it's not 0 then explode it from | character to add database each one
        $files = array_remove($contentData, 'files');
        $fileIds = $files !== '0' ? explode('|', $files) : [];

        DB::beginTransaction();
        try {
            // Create Content
            $content = Content::create($contentData);

            // Create Content Languages
            // Collect all data in one array to make faster sql queries
            $translationData = [];
            foreach ($data as $language => $values) {
                $translationData[] = array_merge($values, [
                    'language' => $language,
                    'content_id' => $content->id,
                    'url' => Str::slug($values['title'])
                ]);
            }
            DB::table('content_translations')->insert($translationData);

            // Create Content Files
            // Collect all data in one array to make faster sql queries
            $filesData = [];
            foreach ($fileIds as $fileId) {
                $filesData[] = [
                    'content_id' => $content->id,
                    'file_id' => $fileId
                ];
            }
            DB::table('content_files')->insert($filesData);

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
        $content = Content::select('parent_id', 'searchable')->find($id);

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

        $files = DB::table('content_files')
            ->where('content_id', $id)
            ->leftJoin('files', 'files.id', 'content_files.file_id')
            ->get()
            ->pluck('path', 'file_id');


        return response()->json([
            'content' => $content,
            'translations' => $translations,
            'files' => $files
        ]);
    }

    public function update(ContentRequest $request, int $id)
    {
        if (!Auth::user()->can('update_contents')) {
            return resJsonUnauthorized();
        }
        $data = $request->validated();
        $contentData = array_remove($data, 'content');

        // Get and unset files from content data and if it's not 0 then explode it from | character to add database each one
        $files = array_remove($contentData, 'files');
        $fileIds = $files !== '0' ? explode('|', $files) : [];

        DB::beginTransaction();
        try {
            // Update Content
            Content::where('id', $id)->update($contentData);

            // Update Content Languages
            $translationData = [];
            foreach ($data as $language => $values) {
                $values['url'] = Str::slug($values['title']);
                DB::table('content_translations')
                    ->where('content_id', $id)
                    ->where('language', $language)
                    ->update($values);
            }

            // Update Content Files
            // Drop all files first, and then collect all data in one array to make faster sql queries
            DB::table('content_files')->where('content_id', $id)->delete();
            $filesData = [];
            foreach ($fileIds as $fileId) {
                $filesData[] = [
                    'content_id' => $id,
                    'file_id' => $fileId
                ];
            }
            DB::table('content_files')->insert($filesData);

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
        $contents = Content::findAllByLocale('contents.id', 'parent_id', 'sequence', 'title')->sortBy('sequence');
        $data = [
            'navigations' => [route('contents.index') => __('contents.self_plural') ,__('contents.sort')],
            'tree' => buildTree($contents, [
                'parentId' => 'parent_id'
            ])
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
            foreach ($data as $key => $datum) {
                // I write this with query builder for better performance, there could be a lot of data to be ordered.
                DB::update('UPDATE contents SET updated_at = ?, parent_id = ?, sequence = ? WHERE id = ?;', [
                    now(),
                    $datum['parent_id'],
                    $key,
                    $datum['id']
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
