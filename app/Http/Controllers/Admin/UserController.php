<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
    Auth, Storage, Hash, Route
};
use App\Models\{User, UserMeta};

class UserController extends Controller
{

    private $ldata = array();

    public function __construct(){
        $this->ldata['title'] = 'Users';
    }

    public function index(){
        $data['title'] = 'Users';
        $data['users'] = User::latest()
                            -> paginate(10);

        return view('admin.users.index', $data);
    }

    public function search(Request $request){

        $q = $request->s;
        $this->ldata['users'] = User::where('name', 'like', "%".$q."%")
                            -> latest()
                            -> paginate(10)
                            -> withQueryString();

        return view('admin.users.index', $this->ldata);
    }

    public function create(){
        return view('admin.users.create', $this->ldata);
    }

    public function store(Request $request){

        self::userValidate($request);

        $last_inserted_id = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ])->id;

        return redirect()->route('users.edit', $last_inserted_id)->with('success', 'User Created');
    }

    public function edit($id){
        $this->ldata['user'] = User::find($id);

        return view('admin.users.edit', $this->ldata);
    }

    public function update(Request $request, $id ){

        self::userValidate($request);

        $input = $request->all();

        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input, array('password'));
        }

        $user = User::find($id);
        $user->update($input);
        return redirect()->route('users.edit', $id)->with('success', 'User Edited');


        /*
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
        */
    }

    public function destroy($id){
        $user = User::find($id);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Deleting success');
    }

    public function myProfile(){
        $data['title'] = 'My Profile';
        // $data['user'] = auth()->user(); //get user data using helper
        $data['user'] = Auth::user(); //get user data using facade

        return view('admin.users.my_profile', $data);
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

        if (\Request::is('admin/users/create')) {
            $pass_validation = 'required|string|confirmed|min:8';
        }else{
            $pass_validation = 'same:password_confirmation';
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$request->id,
            'password' => $pass_validation,
        ]);
    }

    private static function getUserMeta($user_id){
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
