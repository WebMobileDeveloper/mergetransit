<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;

class AdminController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware("guest");
    }

    public function showAdminLoginForm()
    {
        return view("admin.login");
    }

    // public function logout() {
        
    //     Auth::logout();
    //     return view("admin.login");
    // }
}
