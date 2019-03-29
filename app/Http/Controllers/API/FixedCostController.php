<?php

namespace App\Http\Controllers\API;

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
    public $successStatus = 200;
    //
    public function index(Request $request){
        $user_id    =  Auth::user()->id;
        $user_email =  Auth::user()->email;
        $seldate = $request['SelDate'];
       
        // Check if this user is customer or driver
        $customer = Customer::where('email', $user_email)->get();
        if( count($customer) > 0 ) {
            // get all date array by customer
            $customer_id = $customer[0]->id;
            
            $fixedcost = new Fixed_cost();
            $fixedcost = Fixed_cost::where('customer_id', $customer_id)->where('date', 'like', '%'.$seldate.'%')->get();
            if (count ($fixedcost) > 0) {
                return response()->json(['data'=>"success",'fixedcost'=>$fixedcost[0]], 200);
            } else {
                return response()->json(['data'=>"empty"], 200);
            }
            
        } else {
            return response()->json(['data'=>"Failed",'msg'=>'You can not access to this page.'], 200);
        }
        
        
    }

    public function store(Request $request) {
        $update_type = $request['updateType'];
        
        try {
            if($update_type == 'Only') {
                $fixedcost = new Fixed_cost();
            
                $fixedcost->date= $request['SelDate'].'-01';
                $fixedcost->customer_id= $request['customerID'];
                $fixedcost->truck_payment= $request['truck'];
                $fixedcost->trailer_payment= $request['trailer'];
                $fixedcost->insurance= $request['insurance'];
                $fixedcost->communication= $request['communication'];
                $fixedcost->office= $request['office'];
                $fixedcost->payroll= $request['payroll'];
                $fixedcost->total= $request['CostSum'];
                
                $fixedcost->save();
                $current_id = $fixedcost->id;
            } else {
                $seldate_arr = explode("-", $request['SelDate']);
                $current_month = (int)$seldate_arr[1];
                for ($m = $current_month; $m < 13; $m++) {
                    $month = ($m < 10)? '0'.$m : $m;
                    $fixedcost = new Fixed_cost();
            
                    $fixedcost->date= $seldate_arr[0]. '-' . $month . '-01';
                    $fixedcost->customer_id= $request['customerID'];
                    $fixedcost->truck_payment= $request['truck'];
                    $fixedcost->trailer_payment= $request['trailer'];
                    $fixedcost->insurance= $request['insurance'];
                    $fixedcost->communication= $request['communication'];
                    $fixedcost->office= $request['office'];                    
                    $fixedcost->payroll= $request['payroll'];
                    $fixedcost->total= $request['CostSum'];
                    
                    $fixedcost->save();
                    if ($current_month == $m) {
                        $current_id = $fixedcost->id;
                    }
                }
            }
            
            $total_data = app('App\Http\Controllers\API\CostCenterController')->customer_costinfo(null, null, null);
            
            $success = true;
        } catch (\Exception $e) {
            $success = false;
        }
    
        if ($success) {
            return response()->json(['costinfo'=>$total_data,'last_id'=>$current_id], $this->successStatus);
        } else {
            return response()->json(['data'=>"Failed",'response'=>$update_type], 200);
        }
     
    }
    
    public function update(Request $request) {
        
        $update_type = $request['updateType'];
        
        try {
            
            $fixedcost = Fixed_cost::find($request['id']);
            
         
            $fixedcost->truck_payment= $request['truck'];
            $fixedcost->trailer_payment= $request['trailer'];
            $fixedcost->insurance= $request['insurance'];
            $fixedcost->communication= $request['communication'];
            $fixedcost->office= $request['office'];
            $fixedcost->payroll= $request['payroll'];
            $fixedcost->total= $request['CostSum'];
            $fixedcost->save();
            
                
            if($update_type == 'All') {
                
                $fixedcost = Fixed_cost::where('customer_id', $request['customerID'])->where('date', '>', $request['SelDate'].'-01')->get();
               
               foreach ($fixedcost as $item) {
                    $fixedcost = Fixed_cost::find($item->id);
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
            $total_data = app('App\Http\Controllers\API\CostCenterController')->customer_costinfo(null, null, null);
            
            $success = true;
        } catch (\Exception $e) {
            $success = false;
        }
    
        if ($success) {
            return response()->json(['costinfo'=>$total_data], $this->successStatus);
        } else {
            return response()->json(['data'=>"Failed"], 200);
        }
     
    }
    
    
}
