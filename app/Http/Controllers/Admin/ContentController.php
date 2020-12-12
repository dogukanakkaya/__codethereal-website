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
            'navigations' => [__('contents.contents')],
            'columns' => [
                ['data' => 'file', 'name' => 'file', 'title' => __('File')],
                ['data' => 'title', 'name' => 'title', 'title' => __('Title')],
                ['data' => 'status', 'name' => 'status', 'title' => __('Status')],
                ['data' => 'parent', 'name' => 'parent', 'title' => __('Parent')],
                ['data' => 'created_at', 'name' => 'created_at', 'title' => __('global.created_at')],
                ['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable' => false, 'className' => 'dt-actions'],
            ],
            'parents' => Content::findAllByLocale('contents.id', 'title', 'created_at')->pluck('title', 'id')->toArray()
        ];
        return view('admin.contents.index', $data);
    }

    public function datatable()
    {
        $data = Content::findAllByLocale('contents.id', 'title', 'parent_id', 'active', 'created_at');
        return Datatables::of($data)
            ->addColumn('file', function ($row) {
                $file = Content::select('path')
                    ->where('contents.id', $row->id)
                    ->leftJoin('content_files', 'content_files.content_id', '=', 'contents.id')
                    ->leftJoin('files', 'files.id', '=', 'content_files.file_id')
                    ->first();
                return isset($file->path) ? '<img src="' . asset('storage/' . $file->path) . '" class="table-img" alt="profile"/>' : '<div class="table-img"></div>';
            })
            ->addColumn('action', function ($row) {
                $actions = [
                    ['title' => '<i class="material-icons-outlined md-18">edit</i> ' . __('global.update'), 'onclick' => '__find(' . $row->id . ')'],
                    ['title' => '<i class="material-icons-outlined md-18">delete</i> ' . __('global.delete'), 'onclick' => '__delete(' . $row->id . ')'],
                ];
                $actions[] = [
                    'separator' => true,
                    'title' => $row->active ? '<i class="material-icons-outlined md-18">close</i> ' . __('make_passive') : '<i class="material-icons-outlined md-18">check</i> ' . __('make_active'),
                ];
                return view('admin.partials.dropdown', ['actions' => $actions]);
            })
            ->addColumn('status', function ($row) {
                return $row->active ? '<span class="badge badge-success"><i class="material-icons-outlined md-18">check</i></span>' : '<span class="badge badge-danger"><i class="material-icons-outlined md-18">close</i></span></span>';
            })
            ->addColumn('parent', function ($row) {
                return Content::findOneByLocale($row->parent_id, 'title')->title ?? '';
            })
            ->rawColumns(['file', 'action', 'status'])
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
}
