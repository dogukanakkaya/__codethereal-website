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
            'navigations' => [__('users.self_plural')],
            'columns' => $this->columns()
        ];
        return view('admin.users.index', $data);
    }

    public function datatable()
    {
        if (!Auth::user()->can('see_users')) {
            return resJsonUnauthorized();
        }
        $data = User::select('users.id', 'path', 'users.name', 'email', 'users.created_at as created_at', 'email_verified_at')
            ->where('rank', '!=', config('user.rank.dev'))
            ->leftJoin('files', 'files.id', 'users.image')
            ->latest()
            ->get();
        return Datatables::of($data)
            ->editColumn('path', function (User $user) {
                $path = isset($user->path) ? asset('storage/' . $user->path) : asset("img/profile.webp");
                return '<img src="' . $path . '" class="table-img" alt="profile"/>';
            })
            ->editColumn('name', fn (User $user) => '<a class="clickable" title="' . $user->id . '" onclick="__find(' . $user->id . ')">' . $user->name . '</a>')
            ->editColumn('email', fn (User $user) => '<a class="clickable" href="mailto:' . $user->email . '">' . $user->email . '</a>')
            ->editColumn('email_verified_at', fn (User $user) => statusBadge($user->email_verified_at !== NULL))
            ->editColumn('created_at', fn (User $user) => date("Y-m-d H:i:s", strtotime($user->created_at)))
            ->addColumn('check_all', fn (User $user) => '<input type="checkbox" onclick="__onCheck()" value="' . $user->id . '" name="checked[]"/>')
            ->addColumn('is_online', fn (User $user) => statusBadge(isOnline($user->id)))
            ->addColumn('action', fn (User $user) => view('admin.partials.dropdown', ['actions' => $this->actions($user->id)]))
            ->rawColumns(['check_all', 'path', 'name', 'email', 'email_verified_at', 'is_online', 'action'])
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
            ->leftJoin('files', 'files.id', 'users.image')
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

    /**
     * Return table actions
     *
     * @param int $id
     * @return array
     */
    private function actions(int $id): array
    {
        return  [
            ['title' => '<i class="material-icons-outlined md-18">edit</i> ' . __('buttons.update'), 'onclick' => '__find(' . $id . ')'],
            ['title' => '<i class="material-icons-outlined md-18">delete</i> ' . __('buttons.delete'), 'onclick' => '__delete(' . $id . ')'],
        ];
    }

    /**
     * Return the table columns
     *
     * @return array[]
     */
    private function columns(): array
    {
        return [
            ['data' => 'path', 'name' => 'path', 'title' => __('users.photo'), 'className' => 'text-center'],
            ['data' => 'name', 'name' => 'name', 'title' => __('users.fullname')],
            ['data' => 'email', 'name' => 'email', 'title' => __('users.email')],
            ['data' => 'email_verified_at', 'name' => 'verified', 'title' => __('users.verified')],
            ['data' => 'is_online', 'name' => 'online', 'title' => 'Online'],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => __('users.created_at'), 'searchable' => false],
            ['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable' => false, 'className' => 'dt-actions'],
        ];
    }
}
