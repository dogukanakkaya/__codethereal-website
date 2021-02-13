<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    public function index()
    {
        if (!Auth::user()->can('see_settings')){
            return back();
        }
        $data = [
            'navigations' => [__('settings.self')],
            'settings' => DB::table('settings')
                ->get()
                ->groupBy('language')
                ->transform(function($item) {
                    return $item->keyBy('name')->pluck('value', 'name');
                })
        ];
        return view('admin.settings.index', $data);
    }

    public function update(Request $request)
    {
        if (!Auth::user()->can('update_settings')){
            return resJsonUnauthorized();
        }

        $reqData = $request->json()->all();

        DB::beginTransaction();
        try {
            // Loop every language
            //$upsertData = [];
            foreach (languages() as $language) {
                // Get active language's data
                $data = $reqData[$language->code];

                // Loop every data for active language and update or create
                foreach ($data as $key => $value) {
                    /*
                    $upsertData[] = [
                        'name' => $key,
                        'language' => $language->code,
                        'value' => $value
                    ];
                    */
                    DB::table('settings')->updateOrInsert(
                        [
                            'name' => $key,
                            'language' => $language->code
                        ],
                        [
                            'value' => $value,
                        ]
                    );
                }
            }
            // TODO: change updateOrInsert with upsert to make faster queries
            //DB::upsert($upsertData, ['name', 'language'], ['value']);

            // Remove the settings cache
            cache()->forget('settings');

            DB::commit();
            return resJson(true);
        } catch (\Exception) {
            DB::rollBack();
            return resJson(false);
        }
    }
}
