<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{

    private $ldata = array();

    public function __construct()
    {
        $this->middleware(['role:Super Admin']);

        $this->ldata['title'] = 'Permissions';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $this->ldata['permissions'] = Permission::paginate(10);
        return view('admin.permissions.index', $this->ldata);
    }

    /**
     * Display search results
     *
     * @param $request
     * @return Response
    */
    public function search(Request $request){
        $q = $request->s;
        $this->ldata['permissions'] = Permission::where('name', 'like', '%'.$q.'%')->paginate(10);
        return view('admin.permissions.index', $this->ldata);
    }

}
