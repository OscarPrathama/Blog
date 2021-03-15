<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PostMeta;

class MediaController extends Controller
{
    function index(){
        $images = PostMeta::all();
        $data['title'] = 'Images';
        // $data['images'] = array();
        foreach($images as $key => $value){
            $get_img_data = json_decode($value->value);
            $data['images'][$key] = $get_img_data->post_image_feature;
        }

        return view('admin.media.index', $data);
    }

    function updateImg(){
        // just name for alt img
    }

    function deleteImg(){

    }
}
