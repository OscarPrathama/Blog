<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostMeta;
// use Illuminate\Support\Facades\Storage;

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
        $data['title'] = 'Create Post';

        return view('admin.posts.create', $data);
    }

    function store(Request $request){

        self::postValidate($request);

        if ($request->hasFile('post_img_feature')) {
            $image = self::imgValidate($request);
        }else{
             $image = '';
        }

        $meta['post_image_feature'] = $image;

        // save the post while getting the id
        $last_inserted_id = Post::create([
            'user_id' => $request->post_author,
            'post_title' => $request->post_title,
            'post_slug' => \Str::slug($request->post_title),
            'post_type' => 'post',
            'post_content' => $request->post_content,
            'post_status' => $request->post_status,
        ])->id;

        PostMeta::create([
            'post_id' => $last_inserted_id,
            'key' => 'post_meta',
            'value' => json_encode($meta)
        ]);

        return redirect()->route('posts-edit', $last_inserted_id)->with('success', 'New post added !');
    }

    function edit($id){
        $data['title'] = 'Edit Post';
        $data['post'] = Post::find($id);

        return view('admin.posts.edit', $data);
    }

    function update(Request $request, $id){

        self::postValidate($request);

        /*

        if ($request->hasFile('post_img_feature')) {
            $image = self::imgValidate($request);
        }else{
             $image = ''; // tetap menyimpan data sebelumnya
        }

        $meta['post_image_feature'] = $image;
        */

        $post = Post::find($id);
        $post->post_title = $request->post_title;
        $post->post_slug = $request->post_slug;
        $post->post_content = $request->post_content;
        $post->post_status = $request->post_status;
        $post->save();

        return redirect()->route('posts-edit', $id)->with('success', 'Post updated !');

    }

    function delete($id){
        $post = Post::find($id);
        $post->delete();
        return redirect()->route('posts-index')->with('success', 'Post are deleted');
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

    private static function postValidate($request){
        $request->validate([
            'post_title' => 'required',
            'post_slug' => 'required|unique:posts|max:150'
        ]);
    }

    private static function imgValidate($request){
        $file = $request->file('post_img_feature');
        if( $file->isValid() ){
            $image['name'] = $file->getClientOriginalName();
            $image['just_name'] = pathinfo($image['name'], PATHINFO_FILENAME);
            $image['slug_name'] = str_replace(' ', '-', strtolower($image['just_name']));
            $image['extension'] = $file->getClientOriginalExtension();
            $image['real_path'] = $file->getRealPath();
            $image['size'] = $file->getSize();
            $image['mime_type'] = $file->getMimeType();
            $image['original_name'] = $file->getClientOriginalName();

            $validated = $request->validate([
                'post_img_feature' => 'image|mimes:jpeg,png,jpg|max:2048',
            ]);
            $destination = '/upload/'.date('Y').'/'.date('m');
            $img_upload_name = $image['slug_name'].'.'.$image['extension'];

            if (\Storage::exists('/public'.$destination.'/'.$img_upload_name)) {
                $i = 1;
                while (\Storage::exists('/public'.$destination.'/'.$img_upload_name)) {
                    $img_upload_name = $image['slug_name'].'-'.$i.'.'.$image['extension'];
                    $i++;
                }
            }

            $request->post_img_feature->storeAs(
                $destination,
                $img_upload_name
            );

            $image['url'] = $destination.'/'.$img_upload_name;

            return $image;
        }
    }

}
