<?php

namespace App\Http\Controllers\Admin\Menu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Menu\GroupRequest;
use App\Models\Admin\Menu\MenuGroup;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class GroupController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('see_menus')) {
            return back();
        }
        $data = [
            'navigations' => [__('menus.group.self_plural')],
            'columns' => $this->columns()
        ];
        return view('admin.menus.index', $data);
    }

    public function datatable()
    {
        if (!Auth::user()->can('see_menus')) {
            return resJsonUnauthorized();
        }
        $data = MenuGroup::latest()->withCount('items')->get();
        return Datatables::of($data)
            ->editColumn('title', fn (MenuGroup $group) => '<a class="clickable" title="' . $group->id . '" onclick="window.location.href = `' . route('menu_items.index', ['groupId' => $group->id]) . '`">' . $group->title . '</a>')
            ->editColumn('created_at', fn (MenuGroup $group) => date("Y-m-d H:i:s", strtotime($group->created_at)))
            ->addColumn('action', fn (MenuGroup $group) => view('admin.partials.single-actions', ['actions' => $this->actions($group->id)]))
            ->rawColumns(['title', 'action'])
            ->make(true);
    }

    public function create(GroupRequest $request)
    {
        if (!Auth::user()->isDev()) {
            return resJsonUnauthorized();
        }
        return resJson(MenuGroup::create($request->validated()));
    }

    public function find(int $id)
    {
        if (!Auth::user()->isDev()) {
            return resJsonUnauthorized();
        }
        return MenuGroup::find($id);
    }

    public function update(GroupRequest $request, int $id)
    {
        if (!Auth::user()->isDev()) {
            return resJsonUnauthorized();
        }
        return resJson(MenuGroup::where('id', $id)->update($request->validated()));
    }

    public function destroy(int $id)
    {
        if (!Auth::user()->isDev()) {
            return resJsonUnauthorized();
        }
        return resJson(MenuGroup::destroy($id));
    }

    public function restore(int $id)
    {
        if (!Auth::user()->isDev()) {
            return resJsonUnauthorized();
        }
        return resJson(MenuGroup::withTrashed()->find($id)->restore());
    }

    /**
     * Return the table actions
     *
     * @param int $id
     * @return \string[][]
     */
    private function actions(int $id): array
    {
        $actions = [
            '<button class="btn btn-dark text-white btn-sm" onclick="window.location.href = `'.route('menu_items.index', ['groupId' => $id]).'`"><i class="material-icons-outlined md-18">remove_red_eye</i></button>'
        ];

        // Only developer can delete and update menu groups
        if (Auth::user()->isDev()) {
            array_push($actions,
                '<button class="btn btn-info text-white btn-sm" onclick="__find(' . $id . ')"><i class="material-icons-outlined md-18">edit</i></button>',
                '<button class="btn btn-danger text-white btn-sm" onclick="__delete(' . $id . ')"><i class="material-icons-outlined md-18">delete</i></button>'
            );
        }
        return $actions;
    }

    /**
     * Return the table columns
     *
     * @return array[]
     */
    private function columns(): array
    {
        return [
            ['data' => 'title', 'name' => 'title', 'title' => __('menus.group.title')],
            ['data' => 'items_count', 'name' => 'items_count', 'title' => __('menus.item.count')],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => __('menus.created_at')],
            ['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable' => false, 'className' => 'dt-actions'],
        ];
    }
}
