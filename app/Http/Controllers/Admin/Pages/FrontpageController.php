<?php

namespace App\Http\Controllers\Admin\Pages;

use App\Http\Controllers\{Controller, HelperController as Helper};
use Illuminate\Http\Request;
use App\Models\{ Post, PostMeta};

class FrontpageController extends Controller
{
    function edit($id){
        $data['title'] = 'Edit Frontpage';
        $data['page'] = Post::where('post_slug', '=', 'frontpage')
                        -> where('post_type', '=', 'page')
                        -> first();

        // get key post_meta
        $get_post_meta = self::getPostMeta($id, 'post_meta');
        $data['post_meta'] = null;
        if ( $get_post_meta ) {
            $data['post_meta'] = json_decode($get_post_meta->value);
        }

        // get key custom field
        $get_custom_field = self::getPostMeta($id, 'custom_field');
        $data['custom_fields'] = null;
        if ( $get_custom_field ) {
            $data['custom_fields'] = (array) json_decode($get_custom_field->value);
        }

        // dd($data['custom_fields']['field_frontpage']);

        return view('admin/pages/frontpage/edit', $data);
    }

    function update(Request $request, $id){

        // image validate
        $sliders = ($request->slider);
        if(!empty($sliders)){
            foreach ($sliders as $key => $value) {
                foreach ($value as $key2 => $value2) {
                    Helper::imgValidate($value2['image']);
                    // Helper::killNoDie($value2);
                    $sliders[$key][$key2]['image'] = 'hello';
                }
            }
        }
        echo '<hr>';
        dd($sliders);
        dd();

        // field validate
        $post_meta = json_encode($request->slider);

        // save to post meta
        $save_post_meta = PostMeta::updateOrCreate(
            ['post_id' => $request->post_id], // where
            ['key' => 'custom_field', 'value' => $post_meta] // data that updated
        );

        return redirect()->route('frontpage-edit', $id)->with('success', 'Frontpage updated !');
    }

    static function getPostMeta($post_id, $meta_key){
        return PostMeta::where( 'post_id', '=', $post_id )
                            -> where( 'key', '=', $meta_key )
                            -> first();
    }

}
