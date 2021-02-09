<?php

namespace App\Http\Controllers\Admin\Dev;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class CLIController extends Controller
{
    public function index()
    {
        $data = [
            'commands' => DB::table('commands')->get(),
            'navigations' => ['CLI']
        ];
        return view('admin.cli.index', $data);
    }

    public function run()
    {
        $command = request('command', 0);
        if (DB::table('commands')->where('command', $command)->exists()){
            Artisan::call($command);
            return resJson(1);
        }
        return resJsonUnauthorized();
    }
}
