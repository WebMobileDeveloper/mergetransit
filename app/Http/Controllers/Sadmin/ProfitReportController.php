<?php

namespace App\Http\Controllers\Sadmin;

use App\Http\Controllers\Controller;
use App\Customer;
use App\User;
use App\Detail; 
use App\Driver; 
use App\Maintenance;
use App\Load_expense;
use App\Fixed_cost;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Mail;
use File;
use PDF;

class ProfitReportController extends Controller
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
  
    public function index(Request $request){

        $date_range = array(
            'start_date'=> date('Y-m-d', strtotime('-1 years')),
            'end_date'=>date('Y-m-d')
        );

        // get revenue
        

        return view('sadmin.profit.index',compact('date_range'));
    }
   
}