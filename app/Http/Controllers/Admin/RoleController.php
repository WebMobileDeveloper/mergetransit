<?php

namespace App\Http\Controllers\Admin;

use App\Role;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class RoleController extends Controller
{
    public function __construct()
    {
       $this->middleware('auth');
    }
    public function index()
    {
        $roles = Role::paginate(5);
        return view('admin.roles.index',compact('roles'));
    }
    
    public function create()
    {
        return view('admin.roles.roleAdd');
    }

    public function store(Request $request)
    {
        $role= new Role();
        $role->title= $request['title'];
        $role->description= $request['desc'];
        $role->save();
        return redirect('admin/roles');

    }

    public function show($id)
    {
        return redirect('admin/roles');
    }

    public function edit($id)
    {
        $role = Role::find($id);
        return view('admin.roles.roleEdit',compact('role'));
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $role->description   = Input::get('desc');
        $role->title          = Input::get('title');
       
        $role->save();
        return redirect('admin/roles');
    }

    public function destroy($id)
    {
        $user = Role::find($id);
        $user->delete();
        return redirect('admin/roles');
    }
}

