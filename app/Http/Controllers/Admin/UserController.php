<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
    Auth, Storage, Hash, Route
};
use App\Models\{User, UserMeta};

use Spatie\Permission\Models\Role;
use DB;

class UserController extends Controller
{

    private $ldata = array();

    public function __construct(){
        $this->middleware(['role:Admin']);

        $this->ldata['title'] = 'Users';
    }

    public function index(){
        $data['title'] = 'Users';
        $data['users'] = User::latest()-> paginate(10);
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
        $this->ldata['roles'] = Role::pluck('name', 'name')->all();

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

    public function show($id){
        $this->ldata['user'] = User::leftJoin('posts', 'posts.user_id', '=', 'users.id')
                                    -> select('users.id', 'users.name', 'users.email', 'users.created_at', DB::raw('count(posts.id) as post_count'))
                                    -> groupBy('users.id')
                                    -> where('users.id', $id)
                                    -> first();
        $this->ldata['user']['roles'] = $this->ldata['user']->roles->all();

        return view('admin.users.show', $this->ldata);
    }

    public function edit($id){
        $this->ldata['user'] = User::find($id);
        $user_meta = self::getUserMeta($id);
        if(!empty($user_meta->value)){
            $this->ldata['user']['image'] = json_decode($user_meta->value);
        }
        $this->ldata['roles'] = Role::pluck('name', 'name')->all();
        $this->ldata['userRole'] = $this->ldata['user']->roles->pluck('name', 'name')->all();

        return view('admin.users.edit', $this->ldata);
    }

    public function update(Request $request, $id ){

        self::userValidate($request);

        /**
         * if ada user_img
         *  if ada user_img && !kosong user_meta->value
         *  else ada user_img && kosong user_meta->value
         * else tdk ada user_img
        */

        if ($request->hasFile('user_img')) {
            $image = self::imageValidate($request);
            $user_image = json_encode($image);
            $user_meta = self::getUserMeta($id);
            if (isset($user_meta) && $user_meta->value !== '' && $user_meta->value !== null) {
                $img_old_path = json_decode($user_meta->value);
                if( !empty($img_old_path->url) ){
                    Storage::delete('/public'.$img_old_path->url);
                    $user_meta->value = $user_image;
                    $user_meta->save();
                }
            }elseif( isset($user_meta) && ($user_meta->value == '' || $user_meta->value == null) ){
                $user_meta->value = $user_image;
                $user_meta->save();
            }elseif(!isset($user_meta)){
                $save_user_meta = UserMeta::updateOrCreate(
                    ['user_id' => $id],
                    ['key' => 'user_img'],
                    ['value' => $user_image],
                );
            }
        }

        $input = $request->all();

        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input, array('password'));
        }

        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id', $id)->delete();
        $user->assignRole($request->input('roles'));

        return redirect()->route('users.edit', $id)->with('success', 'User Edited');

    }

    public function destroy($id){
        $user = User::find($id);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Deleting success');
    }

    public function removeImage(){
        $user_meta = UserMeta::where('user_id', '=', $_POST['user_id'])->firstOrFail();
        $user_meta->value = '';
        $user_meta->save();
    }

    private static function userValidate($request){

        if (\Request::is('admin/users/create')) {
            $pass_validation = 'required|string|confirmed|min:8';
        }else{
            $pass_validation = 'same:password_confirmation';
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$request->user_id,
            'password' => $pass_validation,
        ]);
    }

    private static function imageValidate($request){
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

            if($validated){
                $destination = '/upload/'.date('Y').'/'.date('m');
                $img_upload_name = $image['slug_name'].'.'.$image['extension'];

                if (Storage::exists('/public'.$destination.'/'.$img_upload_name)) {
                    $i = 1;
                    while (Storage::exists('/public'.$destination.'/'.$img_upload_name)) {
                        $img_upload_name = $image['slug_name'].'-'.$i.'.'.$image['extension'];
                        $i++;
                    }
                }
                $request->user_img->storeAs('/public'.$destination, $img_upload_name);

                $image['url'] = $destination.'/'.$img_upload_name;
                return $image;
            }else{
                return false;
            }
        }
    }

    private static function getUserMeta($user_id, $user_meta = 'user_img'){
        return UserMeta::where( 'user_id', '=', $user_id )
                            -> where( 'key', '=', $user_meta )
                            -> first();
    }

}
