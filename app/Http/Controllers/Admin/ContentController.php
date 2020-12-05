<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Models\Admin\Content;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class ContentController extends Controller
{
    public function index()
    {
        if (!Auth::user()->can('see_contents')){
            return back();
        }
        $data = [
            'navigations' => [__('contents.contents')],
            'columns' => [
                ['data' => 'created_at', 'name' => 'created_at', 'title' => __('global.created_at')],
                ['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable' => false, 'className' => 'dt-actions'],
            ]
        ];
        return view('admin.contents.index', $data);
    }

    public function datatable()
    {
        $data = Content::all();
        return Datatables::of($data)
            ->addColumn('action', function($row){
                $actions = [
                    ['title' => '<i class="material-icons-outlined md-18">edit</i> ' . __('global.update'), 'onclick' => '__find('.$row->id.')'],
                    ['title' => '<i class="material-icons-outlined md-18">delete</i> ' . __('global.delete'), 'onclick' => '__delete('.$row->id.')'],
                ];
                return view('admin.partials.dropdown', ['actions' => $actions]);
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
