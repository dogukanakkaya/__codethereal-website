<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index()
    {
        if (!Auth::user()->can('see_users')) {
            return back();
        }
        $data = [
            'navigations' => [__('users.users')],
            'columns' => [
                ['data' => 'path', 'name' => 'path', 'title' => __('users.photo'), 'className' => 'text-center'],
                ['data' => 'name', 'name' => 'name', 'title' => __('users.fullname')],
                ['data' => 'email', 'name' => 'email', 'title' => __('users.email')],
                ['data' => 'position', 'name' => 'position', 'title' => __('users.position')],
                ['data' => 'email_verified_at', 'name' => 'verified', 'title' => __('users.verified')],
                ['data' => 'is_online', 'name' => 'online', 'title' => 'Online'],
                ['data' => 'created_at', 'name' => 'created_at', 'title' => __('global.created_at')],
                ['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable' => false, 'className' => 'dt-actions'],
            ]
        ];
        return view('admin.users.index', $data);
    }

    public function datatable()
    {
        if (!Auth::user()->can('see_users')) {
            return resJsonUnauthorized();
        }
        $data = User::select('users.id', 'path', 'users.name', 'email', 'position', 'users.created_at as created_at', 'email_verified_at')
            ->where('rank', '!=', config('user.rank.dev'))
            ->leftJoin('files', 'files.id', '=', 'users.image')
            ->latest()
            ->get();
        return Datatables::of($data)
            ->editColumn('path', function (User $user) {
                $path = isset($user->path) ? asset('storage/' . $user->path) : asset("img/profile.webp");
                return '<img src="' . $path . '" class="profile-img" alt="profile"/>';
            })
            ->editColumn('email_verified_at', function (User $user) {
                return $user->email_verified_at !== NULL ? '<span class="badge badge-success">' . __('global.yes') . '</span>' : '<span class="badge badge-danger">' . __('global.no') . '</span>';
            })
            ->addColumn('action', function ($row) {
                $actions = [
                    ['title' => '<i class="material-icons-outlined md-18">edit</i> ' . __('global.update'), 'onclick' => '__find(' . $row->id . ')'],
                    ['title' => '<i class="material-icons-outlined md-18">delete</i> ' . __('global.delete'), 'onclick' => '__delete(' . $row->id . ')'],
                ];
                return view('admin.partials.dropdown', ['actions' => $actions]);
            })
            ->addColumn('is_online', function ($row) {
                return isOnline($row->id) ? '<span class="badge badge-success">' . __('global.yes') . '</span>' : '<span class="badge badge-danger">' . __('global.no') . '</span>';
            })
            ->rawColumns(['path', 'action', 'email_verified_at', 'is_online'])
            ->make(true);
    }

    public function create(UserRequest $request)
    {
        if (!Auth::user()->can('create_users')) {
            return resJsonUnauthorized();
        }
        $data = $request->validated();

        DB::beginTransaction();
        try {
            // User is not active until he/she creates a password, so make password random.
            $data['password'] = Hash::make(Str::random(32));

            $user = User::create($data);

            // Create user permissions
            $user->givePermissionTo(request('role_permissions', []));

            // Send an email to user to create a password
            $status = Password::sendResetLink(['email' => $user->email]);

            if ($status === Password::RESET_LINK_SENT) {
                DB::commit();
                return resJson(true);
            }
            return resJson(false);
        } catch (\Exception) {
            DB::rollBack();
            return resJson(false);
        }


    }

    public function find(int $id)
    {
        if (!Auth::user()->can('see_users')) {
            return resJsonUnauthorized();
        }
        $user = User::find($id);

        // None developer user, can't see a developer user
        if ($user->isDev() && !Auth::user()->isDev()) {
            return resJson(false);
        }

        // TODO: remove "pivot" key from permissions array
        return User::with('permissions:id,name')
            ->select('users.id', 'users.name', 'email', 'position', 'about', 'image', 'path')
            ->leftJoin('files', 'files.id', '=', 'users.image')
            ->find($id);
    }

    public function update(UserRequest $request, int $id)
    {
        if (!Auth::user()->can('update_users')) {
            return resJsonUnauthorized();
        }
        $data = $request->validated();

        DB::beginTransaction();
        try {
            // Find user to sync permissions
            $user = User::find($id);

            // None developer user, can't update a developer user
            if ($user->isDev() && !Auth::user()->isDev()) {
                return resJson(false);
            }

            User::where('id', $id)->update($data);

            // Sync user permissions with new ones
            $user->syncPermissions(request('role_permissions', []));

            DB::commit();
            return resJson(true);
        } catch (\Exception) {
            DB::rollBack();
            return resJson(false);
        }
    }

    public function destroy(int $id)
    {
        if (!Auth::user()->can('delete_users')) {
            return resJsonUnauthorized();
        }
        $user = User::find($id);
        // None developer user, can't delete a developer user
        if ($user->isDev() && !Auth::user()->isDev()) {
            return resJson(false);
        }
        return resJson(User::destroy($id));
    }

    public function restore(int $id)
    {
        if (!Auth::user()->can('delete_users')) {
            return resJsonUnauthorized();
        }
        $user = User::withTrashed()->find($id);

        // None developer user, can't undo a developer user
        if ($user->isDev() && !Auth::user()->isDev()) {
            return resJson(false);
        }
        return resJson($user->restore());
    }
}
