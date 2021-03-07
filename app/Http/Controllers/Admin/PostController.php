<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    function index(){
        $data['title'] = 'Posts';
        $data['posts'] = Post::getPosts();

        return view('admin.posts.index', $data);
    }

    function search(Request $request){
        $data['title'] = 'Posts';
        $q = $request->s;
        $data['posts'] = Post::leftJoin('users', 'posts.user_id', '=', 'users.id')
                            -> select('posts.*', 'users.name as post_author')
                            -> where('post_title', 'like', "%".$q."%")
                            -> orWhere('post_status', 'like', "%".$q)
                            -> latest()
                            -> paginate(10)
                            -> withQueryString();

        return view('admin.posts.index', $data);
    }

    function create(){
        echo 'post create';
    }

    function bulkAction(Request $request){
        if( $request->input('bulks') ){
            Post::whereIn('id', $request->input('bulks'))->delete();
            return redirect()->route('posts-index')->with('success', 'All selected Post deleted !');
        }else{
            return redirect()->route('posts-index');
        }
    }

    function quickUpdate(Request $request){
        // dd($request)
        $post = Post::updateOrCreate(
            ['id' => $request->post_id],
            [
                'post_title' => $request->new_post_title,
                'post_status' => $request->new_post_status
            ],
        );
        $new_post_status = Post::find($request->post_id)->post_status;
        return response()->json([
            'success' => 'Saved',
            'new_title' => Post::find($request->post_id)->post_title,
            'new_status' => $new_post_status == 1 ? 'Publish' : 'Draft'
        ]);
    }
}
