<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;

class MenuController extends Controller
{

    function index(){
        $data['title'] = 'Menu';
        $data['main_menu'] = Menu::where('menu_position', '=', 'main_menu')->first();
        $data['main_menu']['menus'] = json_decode($data['main_menu']->menus);

        return view('admin.menu.index', $data);
    }

    function _updateOrCreate(Request $request){
        Menu::updateOrCreate(
            ['menu_position' => $request->menu_position],
            [
                'menus' => json_encode($request->menus),
            ]
        );

        return redirect()->route('menus.index')->with('success', 'Data saved !');
    }

}
