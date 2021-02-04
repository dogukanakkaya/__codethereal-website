<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function index()
    {
        $data = [
            'navigations' => []
        ];
        return view('admin.home.index', $data);
    }

    /*
    public function privateStorage($folders, $hash, $extension)
    {
        $folder = str_replace(',', '/', $folders);
        return Storage::get('private/' . $folder . '/' . $hash . '.' . $extension);
    }
    */
}
