<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProfileRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class ProfileController extends Controller
{
    public function index()
    {
        $data = [
            'navigations' => [__('users.profile')]
        ];
        return view('admin.profile.index', $data);
    }

    public function update(ProfileRequest $request)
    {
        $data = $request->validated();
        $id = Auth::id();
        $update = User::where('id', $id)->update($data);
        return resJson($update);
    }

    public function requestPassword()
    {
        $user = Auth::user();

        $status = Password::sendResetLink(['email' => $user->email]);

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }
}
