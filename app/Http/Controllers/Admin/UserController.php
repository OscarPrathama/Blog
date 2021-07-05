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

    public function edit($id){
        $this->ldata['user'] = User::find($id);
        $this->ldata['roles'] = Role::pluck('name', 'name')->all();
        $this->ldata['userRole'] = $this->ldata['user']->roles->pluck('name', 'name')->all();

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
        DB::table('model_has_roles')->where('model_id', $id)->delete();

        return redirect()->route('users.edit', $id)->with('success', 'User Edited');

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
}
