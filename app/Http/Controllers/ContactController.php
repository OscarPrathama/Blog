<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    function index(){
        $data['title'] = 'Contact Us';

        return view('contact', $data);
    }
}
