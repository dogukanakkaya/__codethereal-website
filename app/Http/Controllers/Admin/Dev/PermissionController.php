<?php

namespace App\Http\Controllers\Admin\Dev;

use App\Http\Controllers\Controller;

use App\Http\Requests\PermissionRequest;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $data = [
            'navigations' => [__('permissions.permission')],
            'permissionGroups' => Permission::all()->groupBy('group')
        ];
        return view('admin.permissions.index', $data);
    }

    /*
     * We do not have datatable on permissions, so we just return a view
     */
    public function ajaxList()
    {
        return response()
            ->view('admin.permissions.ajax-list', ['permissionGroups' => Permission::all()->groupBy('group')], 200)
            ->header('Content-Type', 'application/html');
    }

    public function create(PermissionRequest $request)
    {
        return resJson(Permission::create($request->validated()));
    }

    public function destroy(int $id)
    {
        return resJson(Permission::destroy($id));
    }

    public function find(int $id)
    {
        return Permission::select('name','title','group')->find($id);
    }

    public function update(PermissionRequest $request, int $id)
    {
        return resJson(Permission::where('id', $id)->update($request->validated()));
    }

    public function checkboxesView()
    {
        return response()
            ->view('admin.permissions.checkboxes', ['permissionGroups' => Permission::all()->groupBy('group')], 200)
            ->header('Content-Type', 'application/html');
    }
}
