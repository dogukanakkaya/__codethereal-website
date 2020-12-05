<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ContentRequest;
use App\Models\Admin\Content;
use Illuminate\Support\Facades\Auth;
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
                ['data' => 'title', 'name' => 'title', 'title' => __('Title')],
                ['data' => 'created_at', 'name' => 'created_at', 'title' => __('global.created_at')],
                ['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable' => false, 'className' => 'dt-actions'],
            ]
        ];
        return view('admin.contents.index', $data);
    }

    public function datatable()
    {
        $data = Content::select('title', 'created_at')
            ->leftJoin('content_translations', 'content_translations.content_id', '=', 'contents.id')
            ->where('language', app()->getLocale())
            ->get();
        return Datatables::of($data)
            ->addColumn('action', function ($row) {
                $actions = [
                    ['title' => '<i class="material-icons-outlined md-18">edit</i> ' . __('global.update'), 'onclick' => '__find(' . $row->id . ')'],
                    ['title' => '<i class="material-icons-outlined md-18">delete</i> ' . __('global.delete'), 'onclick' => '__delete(' . $row->id . ')'],
                ];
                return view('admin.partials.dropdown', ['actions' => $actions]);
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        if (!Auth::user()->can('create_contents')) {
            return back();
        }
        $data = [
            'navigations' => [route('contents.index') => __('contents.contents'), __('global.add_new', ['name' => __('contents.content')])],
            'parents' => Content::select('contents.id', 'title', 'created_at')
                ->leftJoin('content_translations', 'content_translations.content_id', '=', 'contents.id')
                ->where('language', app()->getLocale())
                ->get()
                ->pluck('title', 'id')->toArray()
        ];
        return view('admin.contents.add', $data);
    }

    public function store(ContentRequest $request)
    {
        if (!Auth::user()->can('create_contents')) {
            return resJsonUnauthorized();
        }
        $data = $request->validated();
        dd($data);
    }
}
