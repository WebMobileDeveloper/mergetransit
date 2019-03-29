<?php

namespace App\Http\Controllers\Sadmin;

use App\Customer;
use App\User;
use App\Detail; 
use App\Driver; 
use App\Maintenance;
use App\Load_expense;
use App\Fixed_cost;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Mail;
use File;
use PDF;

class UpdatePWController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = Auth::user();
            $customer_email = Auth::user()->email;
            $customer = Customer::where("email", $customer_email)->get();
            $this->customer_id = $customer[0]->id;

            return $next($request);
        });    
    }
  
    public function index(){
        return view('sadmin.changepw.changepw');
    }
    public function update(Request $request)
    {
        $user = Auth::user();
        $hashedPassword = $user->password;
        
        if (Hash::check($request['old_pass'], $hashedPassword)) {
                $users = User::findOrFail($user->id);
                $users->password=bcrypt($request['new_pass']);
                $users->save();
                return view('sadmin.changepw.changepw');
        }else{
            return view('sadmin.changepw.changepw');
        }
    }
}