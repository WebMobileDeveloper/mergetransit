<?php

namespace App\Http\Controllers\Sadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Driver;
use App\Contact_list;
use App\Customer;
use App\User;
use App\Detail; 
use App\Fixed_cost;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Mail;
use PDF;

class FixedCostController extends Controller
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
        if(empty($request->seldate)){
            $seldate = date('Y-m');
        } else {
            $seldate = $request->seldate;
        }       
        
        $fixedcost = Fixed_cost::where('customer_id', $this->customer_id)->where('date', 'like', '%'.$seldate.'%')->get();
        return view('sadmin.fixedcost.index', compact('fixedcost','seldate'));
        
    }

    public function store(Request $request) {
        $currentdate = date('Y-m');
        $seldate = $request['sel_date'];
        try {
            if($currentdate != $request['sel_date']) {
                if ($request['fixed_id'] == 0) {
                    $fixedcost = new Fixed_cost();   
                } else {
                    $fixedcost = Fixed_cost::find($request['fixed_id']);
                }
               
                $fixedcost->date= $request['sel_date'].'-01';
                $fixedcost->customer_id= $this->customer_id;
                $fixedcost->truck_payment= $request['truck'];
                $fixedcost->trailer_payment= $request['trailer'];
                $fixedcost->insurance= $request['insurance'];
                $fixedcost->communication= $request['communication'];
                $fixedcost->office= $request['office'];
                $fixedcost->payroll= $request['payroll'];
                $fixedcost->total= $request['CostSum'];
                
                $fixedcost->save();
            } else {


                $seldate_arr = explode("-", $request['sel_date']);
                $current_year = (int)$seldate_arr[0];
                $current_month = (int)$seldate_arr[1];
                for ($m = $current_month; $m < 13; $m++) {
                    $month = ($m < 10)? '0'.$m : $m;
                    $checkdate =  $current_year ."-". $month;
                    $checkfixedcost = Fixed_cost::where('customer_id', $this->customer_id)->where('date', 'like', '%'.$checkdate.'%')->get();
                    // var_dump($checkfixedcost);
                    if (empty($checkfixedcost[0])) {
                        $fixedcost = new Fixed_cost();
                    } else {
                        $fixedcost = Fixed_cost::find($checkfixedcost[0]->id);
                    }

            
                    $fixedcost->date= $seldate_arr[0]. '-' . $month . '-01';
                    $fixedcost->customer_id= $this->customer_id;
                    $fixedcost->truck_payment= $request['truck'];
                    $fixedcost->trailer_payment= $request['trailer'];
                    $fixedcost->insurance= $request['insurance'];
                    $fixedcost->communication= $request['communication'];
                    $fixedcost->office= $request['office'];
                    $fixedcost->payroll= $request['payroll'];
                    $fixedcost->total= $request['CostSum'];
                    
                    $fixedcost->save();
                    
                }
            }    
            
            $success = true;
        } catch (\Exception $e) {
            $success = false;
        }
    
        if ($success) {
            $fixedcost = Fixed_cost::where('customer_id', $this->customer_id)->where('date', 'like', '%'.$seldate.'%')->get();
            // return view('sadmin.fixedcost.index', compact('fixedcost','seldate'));
            return redirect()->back()->with('success', 'Saved successfully.');
        } else {
            return redirect()->back()->with('error', 'Something error.');
        }
     
    }
    
    
}
