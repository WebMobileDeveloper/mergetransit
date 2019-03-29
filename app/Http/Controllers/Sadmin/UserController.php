<?php

namespace App\Http\Controllers\Sadmin;

use App\Http\Controllers\Controller;
use App\User;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
       $this->middleware('auth');
    }
    public function index()
    {
        $users = DB::table('users');
        $users=$users->join('roles', 'users.role', '=', 'roles.id');
        $users=$users->select('users.*', 'roles.title');
        if(Auth::user()->role ==2) {
            $users=$users->where('users.role',3);
        }
        $users=$users->where('users.role','<>',4);
        $users=$users->orderBy("users.role")->paginate(10);

        return view('admin.users.index',compact('users'));
    }
    
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.userAdd',compact('roles'));
    }

    public function store(Request $request)
    {
        $user= new User();
        $user->firstname= $request['firstname'];
        $user->lastname= $request['lastname'];
        $user->email= $request['email'];
        $user->phone= $request['phone'];
        $user->role= $request['role'];
        $user->password= bcrypt("123456");
        $user->save();
        return redirect('admin/users');

    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::all();
        return view('admin.users.userEdit',compact('user','roles'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->firstname   = Input::get('firstname');
        $user->lastname   = Input::get('lastname');
        $user->email   = Input::get('email');
        $user->phone          = Input::get('phone');
        $user->role          = Input::get('role');
       
        $user->save();
        return redirect('admin/users');
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect('admin/users');
    }

    public function setactive(Request $request){        
        $id = $request->id;
        $isActive = $request->isActive;

        $user = User::findOrFail($id);
        $user->is_active = $isActive;
        
        if($user->save()) {
            die (json_encode("ok"));
        }else{
            die (json_encode("fail"));
        }
    }
}
