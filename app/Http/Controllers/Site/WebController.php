<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;

class WebController extends Controller
{
    public function index()
    {
        $data = [

        ];
        return view('site.index', $data);
    }
}
