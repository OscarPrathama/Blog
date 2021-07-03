<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HelperController extends Controller{

    static function imgValidate($request){
        dd($request);
    }

    static function killNoDie($data){
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }

}
