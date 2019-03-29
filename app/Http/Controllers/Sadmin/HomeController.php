<?php

namespace App\Http\Controllers\Sadmin;

use App\Http\Controllers\Controller;
use App\Driver;
use App\Customer;
use App\User;
use App\Detail;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    //

    protected $customer_id;
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
    public function __constructor()
    {
        $this->middleware("auth");
    }

    public function index()
    {
     
        //total dispatch input number
        $details = Detail::join('drivers','drivers.id', '=', 'details.driver_id')->where('drivers.company_id',$this->customer_id);

        $total_detail = $details->get()->count();
        //total amount revenue
        $total_revenue = $details->sum('revenue');      

        //billed amount
        $total_miles = $details->sum('mile');
        $total_dhmiles = $details->sum('dho');

        $total_miles  = $total_miles + $total_dhmiles ;

        $total_data = app('App\Http\Controllers\API\CostCenterController')->customer_costinfo(null, null, null);

      
        return view("sadmin.home", compact('total_data'));
    }

    
}
