<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $tempData = [
            'navigations' => ['/users' => 'Users', 'Edit User']
        ];
        return view('admin.home.index', $tempData);
    }
}
