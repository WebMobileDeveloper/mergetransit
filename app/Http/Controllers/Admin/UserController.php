<?php

namespace App\Http\Controllers\Admin;

use App\Driver;
use App\Http\Controllers\Controller;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $users = DB::table('users');
        $users = $users->join('roles', 'users.role', '=', 'roles.id');
        $users = $users->select('users.*', 'roles.title');
        if (Auth::user()->role == 2) {
            $users = $users->where('users.role', 3);
        }
        $users = $users->where('users.role', '<>', 4);
        $users = $users->orderBy("users.role")->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.users.userAdd', compact('roles'));
    }

    public function store(Request $request)
    {
        $user = new User();
        $user->firstname = $request['firstname'];
        $user->lastname = $request['lastname'];
        $user->email = $request['email'];
        $user->phone = $request['phone'];
        $user->role = $request['role'];
        $user->password = bcrypt("123456");
        $user->save();
        return redirect('admin/users');

    }

    public function show($id)
    {
        return redirect('admin/users');
    }

    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::all();
        return view('admin.users.userEdit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->firstname = Input::get('firstname');
        $user->lastname = Input::get('lastname');
        $user->email = Input::get('email');
        $user->phone = Input::get('phone');
        $user->role = Input::get('role');

        $user->save();
        return redirect('admin/users');
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect('admin/users');
    }

    public function setactive(Request $request)
    {
        $id = $request->id;
        $isActive = $request->isActive;

        $user = User::findOrFail($id);
        $user->is_active = $isActive;

        if ($user->save()) {
            die(json_encode("ok"));
        } else {
            die(json_encode("fail"));
        }
    }

    public function cleanusers()
    {
        $driver_users = User::where('role', 0)->get();
        foreach ($driver_users as $driveruser) {

            $driver = Driver::where('user_id', $driveruser->id)->get();
            if (isset($driver) && count($driver) != 0) {

            } else {
                $missing_user = User::findOrFail($driveruser->id);
                if ($missing_user->delete()) {
                echo 'Remived ' . $driveruser->id . "-" . $driveruser->firstname . " " . $driveruser->lastname . '<br>';
                }
            }

        }
    }
}
