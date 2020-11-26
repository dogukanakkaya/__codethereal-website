<?php

namespace App\Http\Controllers\Admin\Menu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Menu\StoreGroup;
use App\Models\Menu\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::user()->can('see_menus')){
            return back();
        }
        if ($request->ajax()) {
            $data = Group::latest()->withCount('items')->get();
            return Datatables::of($data)
                ->addColumn('action', function($row){
                    $actions = [
                        ['title' => '<i class="fas fa-eye fa-fw"></i> ' . __('global.detail'), 'onclick' => 'window.location.href = "'.route('menu_items.index', ['groupId' => $row->id]).'"']
                    ];

                    // Only developer can delete and update menu groups
                    if (Auth::user()->isDev()){
                        array_push($actions,
                            ['title' => '<i class="fas fa-pencil-alt fa-fw"></i> ' . __('global.update'), 'onclick' => '__find('.$row->id.')'],
                            ['title' => '<i class="fas fa-trash fa-fw"></i> ' . __('global.delete'), 'onclick' => '__delete('.$row->id.')'],
                        );
                    }
                    return view('admin.partials.dropdown', ['actions' => $actions]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }else{
            $data = [
                'navigations' => [__('menus.groups')],
                'columns' => [
                    ['data' => 'title', 'name' => 'title', 'title' => __('menus.group_title')],
                    ['data' => 'items_count', 'name' => 'items_count', 'title' => __('menus.item_count')],
                    ['data' => 'created_at', 'name' => 'created_at', 'title' => __('global.created_at')],
                    ['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable' => false, 'className' => 'dt-actions'],
                ]
            ];
            return view('admin.menus.index', $data);
        }
    }

    public function create(StoreGroup $request)
    {
        if (!Auth::user()->isDev()){
            return resJsonUnauthorized();
        }
        $data = $request->validated();
        return resJson(Group::create($data));
    }

    public function find(int $id)
    {
        if (!Auth::user()->isDev()){
            return resJsonUnauthorized();
        }
        return Group::find($id);
    }

    public function update(StoreGroup $request, int $id)
    {
        if (!Auth::user()->isDev()){
            return resJsonUnauthorized();
        }
        $data = $request->validated();
        return resJson(Group::where('id', $id)->update($data));
    }

    public function delete(int $id)
    {
        if (!Auth::user()->isDev()){
            return resJsonUnauthorized();
        }
        return resJson(Group::destroy($id));
    }

    public function restore(int $id)
    {
        if (!Auth::user()->isDev()){
            return resJsonUnauthorized();
        }
        return resJson(Group::withTrashed()->find($id)->restore());
    }
}
