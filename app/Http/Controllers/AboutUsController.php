<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutUsController extends Controller
{
    function index(){
        $data['title'] = 'About Us';

        return view('about_us', $data);
    }
}
