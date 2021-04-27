<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PostMeta;
use Session, Redirect;

class MediaController extends Controller
{
    function index(){
        $images = PostMeta::latest()->get();
        $data['title'] = 'Images';
        foreach($images as $key => $value){
            $get_img_data = json_decode($value->value);
            $data['images'][$key]['id'] = $value->id;
            $data['images'][$key]['data'] = $get_img_data->post_image_feature;
        }

        return view('admin.media.index', $data);
    }

    function updateImg(Request $req){
        $image = PostMeta::findOrFail($req->img_id);
        $decode_img = json_decode($image->value);
        $decode_img->post_image_feature->just_name = $req->img_alt;
        $encode_img = json_encode($decode_img);

        $post_meta = PostMeta::updateOrCreate(
            ['id' => $req->img_id], // condition
            ['value' => $encode_img], // field that want to update
        );

        return response([
            'data' => array(
                'img_id' => $req->img_id,
                'img_alt' => $req->img_alt,
            ),
            'msg' => 'Saved !'
        ]);
    }

    function deleteImg(Request $req){
        $post_meta = PostMeta::findOrFail($req->img_id);
        $decode_img = json_decode($post_meta->value);
        $decode_img->post_image_feature = '';
        $encode_img = json_encode($decode_img);

        $post_meta = PostMeta::updateOrCreate(
            ['id' => $req->img_id],
            ['value' => $encode_img],
        );

        return response([
            'msg' => 'Image deleted !'
        ]);

        // return redirect()->route('media')->with('success', 'Image deleted');
        // return Redirect::back();
    }
}
