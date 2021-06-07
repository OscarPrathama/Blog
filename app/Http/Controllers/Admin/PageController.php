<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostMeta;

class PageController extends Controller{

    function index(Request $request){
        $data['title'] = 'Pages';

        if ($request->s) {
            $data['pages'] = Post::getPagesBySearch($request->s);
        }else{
            $data['pages'] = Post::getPages();
        }

        return view('admin/pages/index', $data);
    }

    function edit($id){
        $data['title'] = 'Edit Page';
        $data['page'] = Post::find($id);
        $get_post_meta = self::getPostMeta($id);
        $data['post_meta'] = $get_post_meta;
        if ( $get_post_meta ) {
            $data['post_meta'] = json_decode($get_post_meta->value);
        }
// dd($data);
        return view('admin.pages.edit', $data);
    }

    static function getPostMeta($post_id){
        return PostMeta::where( 'post_id', '=', $post_id )
                            -> where( 'key', '=', 'post_meta' )
                            -> first();
    }

}
