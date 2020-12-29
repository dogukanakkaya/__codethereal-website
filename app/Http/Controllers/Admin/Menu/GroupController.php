<?php

namespace App\Http\Controllers\Admin\Menu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Menu\GroupRequest;
use App\Models\Admin\Menu\Group;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class GroupController extends Controller
{
    public function index()
    {
        if (!Auth::user()->can('see_menus')) {
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
        $data = Group::latest()->withCount('items')->get();
        return Datatables::of($data)
            ->editColumn('created_at', function (Group $group) {
                return date("Y-m-d H:i:s", strtotime($group->created_at));
            })
            ->addColumn('action', function (Group $group) {
                return view('admin.partials.dropdown', ['actions' => $this->actions($group->id)]);
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create(GroupRequest $request)
    {
        if (!Auth::user()->isDev()) {
            return resJsonUnauthorized();
        }
        return resJson(Group::create($request->validated()));
    }

    public function find(int $id)
    {
        if (!Auth::user()->isDev()) {
            return resJsonUnauthorized();
        }
        return Group::find($id);
    }

    public function update(GroupRequest $request, int $id)
    {
        if (!Auth::user()->isDev()) {
            return resJsonUnauthorized();
        }
        return resJson(Group::where('id', $id)->update($request->validated()));
    }

    public function destroy(int $id)
    {
        if (!Auth::user()->isDev()) {
            return resJsonUnauthorized();
        }
        return resJson(Group::destroy($id));
    }

    public function restore(int $id)
    {
        if (!Auth::user()->isDev()) {
            return resJsonUnauthorized();
        }
        return resJson(Group::withTrashed()->find($id)->restore());
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
            ['title' => '<i class="material-icons-outlined md-18">remove_red_eye</i> ' . __('buttons.detail'), 'onclick' => 'window.location.href = "' . route('menu_items.index', ['groupId' => $id]) . '"']
        ];

        // Only developer can delete and update menu groups
        if (Auth::user()->isDev()) {
            array_push($actions,
                ['title' => '<i class="material-icons-outlined md-18">edit</i> ' . __('buttons.update'), 'onclick' => '__find(' . $id . ')'],
                ['title' => '<i class="material-icons-outlined md-18">delete</i> ' . __('buttons.delete'), 'onclick' => '__delete(' . $id . ')'],
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
