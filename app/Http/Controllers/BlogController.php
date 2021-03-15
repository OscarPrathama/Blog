<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class BlogController extends Controller
{
    function index(){
        $data['title'] = 'Blogs';
        $data['posts'] = Post::leftJoin('users', 'posts.user_id', '=', 'users.id')
                        -> leftJoin('post_metas', 'posts.id', '=', 'post_metas.post_id')
                        -> select('posts.*', 'users.name as post_author', 'post_metas.value as meta_value')
                        -> latest()
                        -> paginate(9)
                        -> onEachSide(1);

        foreach($data['posts'] as $key => $value){
            if (!empty($value['meta_value'])) {
                $data['posts'][$key]['meta_value'] =json_decode($value->meta_value);
            }
        }

        return view('blogs', $data);
    }
}
