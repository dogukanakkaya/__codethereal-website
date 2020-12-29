<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $data = [
            'navigations' => []
        ];
        return view('admin.home.index', $data);
    }
}
