<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Model\User;
use App\Http\Model\UserMeta;
use Illuminate\Support\Facades\Storage;


class UserController extends Controller
{
function myProfile(){
        $data['title'] = 'My Profile';
        // $data['user'] = auth()->user(); //get user data using helper
        $data['user'] = Auth::user(); //get user data using facade

        return view('admin.users.index', $data);
    }

    function update(Request $request, $id ){

        self::userValidate($request);

        if ($request->hasFile('user_img')) {

            $image = self::imgValidate($request);

            // save imgValidate return to variable
            $meta['user_img'] = $image;

            // get current post meta value
            $user_meta = self::getUserMeta($id);

            // update image on folder
            $img_old_path = json_decode($user_meta->value);
            if( !empty($img_old_path->post_image_feature->url) ){
                Storage::delete('/public'.$img_old_path->post_image_feature->url);
            }

            // save to database
            $user_meta->value = json_encode($meta);
            $user_meta->save();

        }

        $meta['image_profile'] = $image;
    }

    private static function imgValidate($request){
        $file = $request->file('user_img');
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
                'user_img' => 'image|mimes:jpeg,png,jpg|max:2048',
            ]);
            $destination = '/upload/'.date('Y').'/'.date('m');
            $img_upload_name = $image['slug_name'].'.'.$image['extension'];

            if (Storage::exists('/public'.$destination.'/'.$img_upload_name)) {
                $i = 1;
                while (Storage::exists('/public'.$destination.'/'.$img_upload_name)) {
                    $img_upload_name = $image['slug_name'].'-'.$i.'.'.$image['extension'];
                    $i++;
                }
            }

            // upload to storage/app/public
            $request->user_img->storeAs(
                '/public'.$destination,
                $img_upload_name
            );

            $image['url'] = $destination.'/'.$img_upload_name;

            return $image;
        }
    }

    private static function userValidate($request){
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ]);
    }

    static function getUserMeta($user_id){
        return UserMeta::where('user_id', '=', $user_id)
                            -> where('key', '=', 'user_image')
                            -> first();
        /*
        return UserMeta::where( 'post_id', '=', $user_id )
                        -> where( 'key', '=', 'user_image' )
                        -> first();
        */
    }
}
