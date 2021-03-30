<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostMeta;

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
            'post_slug' => \Str::slug($request->post_slug),
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
        $get_post_meta = self::getPostMeta($id);
        $data['post_meta'] = null;
        if ( $get_post_meta ) {
            $data['post_meta'] = json_decode($get_post_meta->value);
        }

        return view('admin.posts.edit', $data);
    }

    function update(Request $request, $id){

        self::postValidate($request);

        if ($request->hasFile('post_img_feature')) {

            // validate img
            $image = self::imgValidate($request);

            // save imgValidate return to variable
            $meta['post_image_feature'] = $image;

            // get current post meta value
            $post_meta = self::getPostMeta($id);

            // update image on folder
            $img_old_path = json_decode($post_meta->value);
            \Storage::delete('/public'.$img_old_path->post_image_feature->url);

            // save to database
            $post_meta->value = json_encode($meta);
            $post_meta->save();

        }

        $post = Post::find($id);
        $post->post_title = $request->post_title;
        $post->post_slug = \Str::slug($request->post_slug);
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
            'post_slug' => 'required|max:150|unique:posts,post_slug,'.$request->post_id // Forcing A Unique Rule To Ignore A Given ID
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

            // upload to storage/app/public
            $request->post_img_feature->storeAs(
                '/public'.$destination,
                $img_upload_name
            );

            // upload to public/storage
            // $path = request()->post_img_feature->move(public_path('storage'.$destination), $img_upload_name);

            $image['url'] = $destination.'/'.$img_upload_name;

            return $image;
        }
    }

    static function getPostMeta($post_id){
        return PostMeta::where( 'post_id', '=', $post_id )
                            -> where( 'key', '=', 'post_meta' )
                            -> first();
    }

}
