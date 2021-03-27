<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    function index(){
        $data['title'] = 'Settings';
        $data['site_setting'] = Setting::find(1);
        return view('admin.settings.index', $data);
    }

    function _updateOrCreate(Request $request){
        self::settingValidate($request);

        Setting::updateOrCreate(
            ['id' => $request->setting_id],
            [
                'setting_name' => $request->setting_name,
                'setting_value' => $request->setting_value
            ]
        );

        return redirect()->route('settings.index')->with('success', 'Data saved !');
    }

    // $post_meta = PostMeta::updateOrCreate(
    //     ['id' => $req->img_id], // condition
    //     ['value' => $encode_img], // field that want to update
    // );

    private static function settingValidate(Request $request){
        $request->validate([
            'setting_name' => 'required',
            'setting_value' => 'required',
        ]);
    }

}
