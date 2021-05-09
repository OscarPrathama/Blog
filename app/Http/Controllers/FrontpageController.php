<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostMeta;
use App\Models\Inbox;

class FrontpageController extends Controller
{
    function index(){
        $data['title'] = 'Frontpage';
        $data['slider'] = 'contoh slider';
        $data['posts'] = Post::getFpPosts();
        foreach( $data['posts'] as $key => $value ){
            if (!empty($value['meta_value'])) {
                $data['posts'][$key]['meta_value'] = json_decode($value['meta_value'], true);
            }
        }
        $data['location'] = 'contoh location';

        return view('frontpage', $data);
    }

    function storeInbox(Request $request){

        Inbox::create([
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message,
        ]);

        return response()->json([
            'success' => 'Saved',
        ]);

    }

    function show($slug){
        $data['post'] = Post::where('post_slug', $slug)->firstOrFail();
        $get_post_meta = self::getPostMeta($data['post']->id);
        $data['post_meta'] = null;
        if ( $get_post_meta ) {
            $data['post_meta'] = json_decode($get_post_meta->value);
        }
        $data['title'] = $data['post']->post_title;

        return view('single', $data);
    }

    // belum, msh fail dan tidak diterapkan di navbar
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

    // other function
    static function getPostMeta($post_id){
        return PostMeta::where( 'post_id', '=', $post_id )->first();
    }

}
