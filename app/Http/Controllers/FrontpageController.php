<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class FrontpageController extends Controller
{
    function index(){
        $data['tilte'] = 'Frontpage';
        $data['menus'] = array(
            array(
                'menu_name' => 'Home',
                'menu_link' => ''
            ),
            array(
                'menu_name' => 'Blogs',
                'menu_link' => '#blogs'
            ),
            array(
                'menu_name' => 'About Us',
                'menu_link' => '#about-us'
            ),
            array(
                'menu_name' => 'Contact Us',
                'menu_link' => '#contact-us'
            ),
        );

        return view('frontpage', $data);
    }

    function show($slug){
        $data['post'] = Post::where('post_slug', $slug)->firstOrFail();
        $data['title'] = $data['post']->post_title;

        return view('single', $data);
    }

    // fail, belum diterapin di navbar
    function menu(){
        $data['menus'] = array(
            array(
                'menu_name' => 'Home',
                'menu_link' => ''
            ),
            array(
                'menu_name' => 'Blogs',
                'menu_link' => '#blogs'
            ),
            array(
                'menu_name' => 'About Us',
                'menu_link' => '#about-us'
            ),
            array(
                'menu_name' => 'Contact Us',
                'menu_link' => '#contact-us'
            ),
        );

        return view('layouts.navbar')->with($data);
    }
}
