<?php

namespace App\Http\Controllers\API;

use App\Driver;
use App\Customer;
use App\Http\Controllers\Controller;
use App\User;
use App\Maintenance;
use App\Load_expense;
use App\Fixed_cost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mail;
use Stripe\Stripe;

class AuthController extends Controller
{
    //
    public $successStatus = 200;

    public function login()
    {

        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            if($user->role == 4){
                
                $customers = Customer::join('users', 'users.email', '=', 'customers.email')
                    ->select('customers.id', 'customers.company', 'customers.firstname',
                     'customers.lastname', 'customers.phone', 'customers.image_path',
                      'customers.member_type', 'customers.description','customers.street',
                      'customers.city','customers.state','customers.zipcode')
                    ->where('users.email', Auth::user()->email)->get();
                    
                $drivers = Driver::join('customers', 'customers.id', '=', 'drivers.company_id')
                        ->join('users', 'users.id', '=', 'drivers.user_id')
                        ->select('drivers.id', 'drivers.company_id', 'drivers.employee_id',
                            'users.firstname', 'users.lastname', 'customers.company')
                        ->where('customers.email', Auth::user()->email)->get();
                
                $success['token'] = $user->createToken('merge')->accessToken;
                $success['userId'] = $user->id;
                $success['name'] = $user->name;
                $success['email'] = $user->email;
                $success['companyname'] = $customers[0]->company;
                $success['customersID'] = $customers[0]->id;
                $success['memberType'] = $customers[0]->member_type;
                $success['drivers'] = $drivers;
                $success['role'] = $user->role;
                $success['card_brand'] = $user->card_brand;
                $success['card_last_four'] = $user->card_last_four;
                $success['isActive'] = $user->is_active;
                $success['firstname'] = $customers[0]->firstname;
                $success['lastname'] = $customers[0]->lastname;
                $success['phone'] = $customers[0]->phone;
                $success['street'] = $customers[0]->street;
                $success['city'] = $customers[0]->city;
                $success['state'] = $customers[0]->state;
                $success['zipcode'] = $customers[0]->zipcode;
                $success['description'] = $customers[0]->description;
                $success['image_path'] = $customers[0]->image_path;
                
                
                $reports = Driver::join('details', 'details.driver_id', '=', 'drivers.id')
                ->join('customers', 'customers.id', '=', 'drivers.company_id')
                ->where('customers.email', Auth::user()->email);  
                    
                /******************************************************///get cost total
                $fixed_cost = Fixed_cost::where('customer_id', $customers[0]->id)->sum('total');
                $loadexpense = Load_expense::where('customer_id', $customers[0]->id)->sum('total');
                $maintenance = Maintenance::where('customer_id', $customers[0]->id)->sum('cost'); //->whereYear('date', '=', date("Y"))
               
                $total_cost   = $fixed_cost + $loadexpense+ $maintenance;
                $totalrevenue = $reports->sum('details.revenue');
                $total_mile   = $reports->sum('details.mile');
                $total_dho    = $reports->sum('details.dho');
                
                $success['total_revenue'] = "$".number_format($totalrevenue, 0);
                $success['total_cost']    = "$".number_format($total_cost,0);
                $success['total_profit']  = "$".number_format(($totalrevenue - $total_cost), 0);
                $success['total_mile']    = number_format($total_mile);
                $success['total_dho']     = number_format($total_dho);
                
                $t_miles                  = $reports->sum('details.mile') + $reports->sum('details.dho');
                $success['total_rpm']     = ($t_miles) ? "$".number_format(($totalrevenue/$t_miles), 2) : 0;
                $success['cost_rpm']      = ($t_miles) ? "$".number_format(($total_cost/$t_miles), 2) : 0;
                $success['profit_rpm']    = ($t_miles)? "$".number_format((($totalrevenue - $total_cost)/$t_miles),2) : 0;
                
                
                $cost_info = app('App\Http\Controllers\API\CostCenterController')->get_costinfo(); 

                // insert login time to login table
                DB::table('login')->insert([ "user_id" => $user->id]);

                return response()->json(['userinfo' => $success, 'costinfo'=>$cost_info, 'success' => $success], $this->successStatus);    
                    
            }elseif($user->role == 0){
                if($user->is_active == 0){
                    Auth::logout();
                    return response()->json(['error' => 'Unauthorised'], 401);
                }
                    
                $driver = Driver::join('customers','customers.id','drivers.company_id')
                        ->join('users','users.id','drivers.user_id')
                        ->select('drivers.id', 'drivers.company_id', 'drivers.employee_id','users.firstname', 'users.lastname', 'customers.company', 'customers.id AS customersid')
                        ->where('drivers.user_id', Auth::user()->id)->get();
                
                $success['token'] = $user->createToken('merge')->accessToken;
                $success['userId'] = $user->id;
                $success['name'] = $user->name;
                $success['email'] = $user->email;
                $success['image_path'] = '';
                $success['isActive'] = $user->is_active;
                $success['companyname'] = $driver[0]->company;
                $success['customersID'] = $driver[0]->customersid;
                $success['drivers'] = $driver;
                $success['role'] = $user->role;
                
                $reports = Driver::join('details', 'details.driver_id', '=', 'drivers.id')
                    ->where('drivers.user_id', Auth::user()->id);                
                
                //get cost total
                $loadexpense = Load_expense::where('driver_id', $driver[0]->id)->sum('total');
                $maintenance = Maintenance::where('driver_id', $driver[0]->id)->sum('cost'); //->whereYear('date', '=', date("Y"))
                
                $total_cost =  $loadexpense+ $maintenance;                
                $totalrevenue = $reports->sum('details.revenue');
                $total_mile = $reports->sum('details.mile');
                $total_dho = $reports->sum('details.dho');
                
                $success['total_revenue'] = "$".number_format($totalrevenue, 0);
                $success['total_cost'] = "$".number_format($total_cost, 0);
                $success['total_profit'] = "$".number_format(($totalrevenue - $total_cost), 0);
                $success['total_mile'] = number_format($total_mile);
                $success['total_dho'] = number_format($total_dho);
                
                $t_miles = $reports->sum('details.mile') + $reports->sum('details.dho');
                $success['total_rpm'] = ($t_miles)?"$".number_format(($totalrevenue/$t_miles), 2) : 0;
                $success['cost_rpm']  = ($t_miles)?"$".number_format(($total_cost/$t_miles), 2) : 0;
                $success['profit_rpm']  = ($t_miles)?"$".number_format((($totalrevenue - $total_cost)/$t_miles),2) : 0;

                // insert login time to login table
                DB::table('login')->insert([ "user_id" => $user->id]);
                
                $costinfo = app('App\Http\Controllers\API\CostCenterController')->get_costinfo();
                
                return response()->json(['userinfo' => $success, 'costinfo'=>$costinfo, 'success' => $success], $this->successStatus);      
                
                
            }else{
                Auth::logout();
                return response()->json(['error' => 'Unauthorised'], 401);
            }
            return response()->json(['success' => $success], $this->successStatus);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }
    
    public function emailstatus(Request $request)
    {
        $nb_email_valid = false;
        $nb_apikey = env("NEVERBOUNCE_API_KEY");

        $curl = curl_init();
        $nb_api_check_url = 'https://api.neverbounce.com/v4/single/check?key='.$nb_apikey.'&email='.$request['email'];

        curl_setopt_array($curl, array(
            CURLOPT_URL =>$nb_api_check_url ,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        $res = json_decode($response);

        $valid_result = ['valid', 'catchall', 'unknown'];
        $invalid_result = ['invalid', 'disaposable'];

        if ($res->status == 'success') {
            if (in_array($res->result, $valid_result)) {
                $nb_email_valid = true;
            }
        }

        if ($nb_email_valid == false) {
            return response()->json(['data'=>'InvalidEmail'], $this->successStatus);
        }


        $users = User::where('users.email', $request['email'])->get();
        
        if(count($users) > 0)
            return response()->json(['data'=>'EmailTrue'], $this->successStatus);
        else{
            return response()->json(['data'=>'EmailFalse'], $this->successStatus);
        }


        
    }
    
    public function signup(Request $request)
    {
        
        $users = User::where('users.email', $request['email'])->get();
        
        if(count($users) > 0){
            return response()->json(['data'=>'EmailTrue'], $this->successStatus);
        }

        
        $nb_email_valid = false;
        $nb_apikey = env("NEVERBOUNCE_API_KEY");

        $curl = curl_init();
        $nb_api_check_url = 'https://api.neverbounce.com/v4/single/check?key='.$nb_apikey.'&email='.$request['email'];

        curl_setopt_array($curl, array(
            CURLOPT_URL =>$nb_api_check_url ,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        $res = json_decode($response);

        $valid_result = ['valid', 'catchall', 'unknown'];
        $invalid_result = ['invalid', 'disaposable'];

        if ($res->status == 'success') {
            if (in_array($res->result, $valid_result)) {
                $nb_email_valid = true;
            }
        }

        if ($nb_email_valid == false) {
            return response()->json(['data'=>'InvalidEmail'], $this->successStatus);
        }


        DB::beginTransaction();
        try {
            $user = new User();
            $user->firstname= $request['firstname'];
            $user->lastname= $request['lastname'];
            $user->email= $request['email'];
            $user->phone= $request['phone'];
            $user->password= bcrypt($request['password']);
            $user->role= 4;
            $user->is_active= 1;

            $userSuccess = $user->save();

            $customer= new customer();
            $customer->user_id = $user->id;
            $customer->company= $request['company'];
            $customer->firstname= $request['firstname'];
            $customer->lastname= $request['lastname'];
            $customer->email=$request['email'];
            $customer->phone= $request['phone'];
            $customer->description= $request['desc'];
            $customer->street= $request['street'];
            $customer->city= $request['city'];
            $customer->state= $request['state'];
            $customer->zipcode= $request['zipcode'];
            
            $customer->manual_invoice = 0;
            $customer->image_path = "";
            if (isset($request['memberType'])){
                $customer->member_type = $request['memberType'];
                $customer->card_token = $request['cardTokenID'];

                $stripe_key = env("STRIPE_SECRET_KEY");
                Stripe::setApiKey($stripe_key);
                
                if ($request['memberType'] == 3) {
                    $user->createAsStripeCustomer($request['cardTokenID'], [
                        'email' => $request['email'],
                        "description" => "Mergetransit"
                    ]);
                    $user->save();
                                    
                    $user->charge(12500, [
                        'description' => 'Merget Transit - Optimization',
                    ]);

                } else if ($request['memberType'] == 2) {                       

                    $user->createAsStripeCustomer($request['cardTokenID'], [
                        'email' => $request['email'],
                        "description" => "Mergetransit"
                    ]);
                    $user->save();
                                    
                    $user->charge(9900, [
                        'description' => 'Merget Transit - Organization',
                    ]);
                }


            }else{
                $customer->member_type = 1;
                $customer->card_token = "Free";    
            }
            
            $customer->upgrade_date = date('Y-m-d');
            $customer_success = $customer->save(); 

            DB::commit();
            $success = true;
        }catch (\Exception $e) {
            $success = false;
            DB::rollback();
        }
    
        if ($success) {
            $data = array(
                'username' =>  $request['firstname']." ".$request['lastname'],
                'company' => $request['company'],
                'email' => $request['email'],
                'phone' => $request['phone']
            );    
    
            $mail_status = Mail::send('email_template.signup_notification', $data,function($message) use($data){
                $message->to(env('ADMIN_EMAIL'),'Mergetransit')->subject('New Customer signup');
                $message->from($data['email'], $data['username']);
                $message->replyTo($data['email'], $data['username']);
            });    
            
            return response()->json(['data' => 'success'], $this->successStatus);

        } else {
            return response()->json(['data'=>'fail'], $this->successStatus);
        }
    }

    public function forgotpass(Request $request)
    {
        $email = $request['email'];

        $users= User::where('email',$request['email'])->get();
        if(count($users)>0){
            $codes= time();
            $verifycode=substr($codes,4,6);
            $data = array(
                'type' => 'success',
                'code' => $verifycode,
                'email'=>$request['email']
            );
            
                    $mail_status = Mail::send('email_template.verifycode_email', $data,function($message) use($data){
                        $message->to($data['email'])->subject('Your Verify Code');
                        $message->from(env("ADMIN_EMAIL"),'Mergetransit');
                        $message->replyTo(env("ADMIN_EMAIL"),'Mergetransit');
                    });
            
            
            
           return response()->json(['data'=>$data], $this->successStatus); 
        }else{
            $data = array(
                'type' => 'error',
                'code' => ''
            );
           return response()->json(['data'=>$data], $this->successStatus);
        }
    }
    public function forgotupdate(Request $request)
    {
        $email = $request['email'];
        $users= User::where('users.email',$request['email'])->get();
        
        $users[0]->password=bcrypt($request['newPassword']);
        $users[0]->save();
        return response()->json(['data'=>'success'], $this->successStatus);
    }


    public function logout()
    {
        Auth::logout();
        return response()->json(['success'=>"OK"], $this->successStatus);
    }

    // public function get_costinfo(){
    //      $user = Auth::user();
    //         if($user->role == 4){
    //             return $this->customer_costinfo();
                
    //         }else{
    //             return $this->driver_costinfo();
    //         }
        
    // }
    // public function customer_costinfo(){
        
    //             $user = Auth::user();
    //             $customers = Customer::join('users', 'users.email', '=', 'customers.email')
    //                 ->select('customers.id', 'customers.company', 'customers.firstname', 'customers.lastname', 'customers.phone', 'customers.image_path', 'customers.description')
    //                 ->where('users.email', Auth::user()->email)->get();
        
    //             $reports = Driver::join('details', 'details.driver_id', '=', 'drivers.id')
    //                         ->join('customers', 'customers.id', '=', 'drivers.company_id')
    //                         ->where('customers.email', Auth::user()->email)->where('details.put_date', 'like', '%' . date("Y") . '%');  
                    
    //             //get cost total
    //             $fixed_cost = Fixed_cost::where('customer_id', $customers[0]->id)->where('date', 'like', '%' . date("Y") . '%')->sum('total');
    //             $loadexpense = Load_expense::where('customer_id', $customers[0]->id)->where('date', 'like', '%' . date("Y") . '%')->sum('total');
    //             $maintenance = Maintenance::where('customer_id', $customers[0]->id)->where('date', 'like', '%' . date("Y") . '%')->sum('cost'); //->whereYear('date', '=', date("Y"))
                
    //             $month = Date('m');
    //             $total_cost = ($fixed_cost*$month) + $loadexpense+ $maintenance;
    //             $totalrevenue = $reports->sum('details.revenue');
    //             $total_mile = $reports->sum('details.mile');
    //             $total_dho = $reports->sum('details.dho');
                
    //             $data['total_revenue'] = "$".number_format($totalrevenue, 0);
    //             $data['total_cost'] = "$".number_format($total_cost, 0);
    //             $data['total_profit'] = "$".number_format(($totalrevenue - $total_cost), 0);
    //             $data['total_mile'] = number_format($total_mile);
    //             $data['total_dho'] = number_format($total_dho);
    //             $data['fixed_cost'] = "$".number_format($fixed_cost, 0); 
    //             $data['fixed_cost_y'] = "$".number_format($fixed_cost * $month, 0); 
    //             $data['loadexpense_y'] = "$".number_format($loadexpense, 0); 
    //             $data['maintenance_y'] = "$".number_format($maintenance, 0); 
                
    //             $t_miles = $total_mile + $total_dho;
    //             $data['total_rpm'] = ($t_miles)?"$".number_format(($totalrevenue/$t_miles), 2):0;
    //             $data['cost_rpm']  = ($t_miles)?"$".number_format(($total_cost/$t_miles), 2):0;
    //             $data['profit_rpm']  = ($t_miles)?"$".number_format((($totalrevenue - $total_cost)/$t_miles), 2):0;
    //             $data['ratio'] = number_format($total_cost/$totalrevenue*100, 2) . "%";
                
    //             return $data;
    // }
    // public function driver_costinfo(){
        
    //             $user = Auth::user();
    //             $drivers = Driver::join('customers','customers.id','drivers.company_id')
    //                     ->join('users','users.id','drivers.user_id')
    //                     ->select('drivers.id', 'drivers.company_id', 'drivers.employee_id','users.firstname', 'users.lastname', 'customers.company', 'customers.id AS customersid')
    //                     ->where('drivers.user_id', Auth::user()->id)->get();
                        
    //             $reports = Driver::join('details', 'details.driver_id', '=', 'drivers.id')
    //                 ->where('drivers.user_id', Auth::user()->id)->where('details.put_date', 'like', '%' . date("Y") . '%'); 
                
    //             //get cost total
               
    //             $loadexpense = Load_expense::where('driver_id', $drivers[0]->id)->where('date', 'like', '%' . date("Y") . '%')->sum('total');
    //             $maintenance = Maintenance::where('driver_id', $drivers[0]->id)->where('date', 'like', '%' . date("Y") . '%')->sum('cost'); //->whereYear('date', '=', date("Y"))
                
    //             $month = Date('m');
    //             $total_cost = $loadexpense+ $maintenance;
    //             $totalrevenue = $reports->sum('details.revenue');
    //             $total_mile = $reports->sum('details.mile');
    //             $total_dho = $reports->sum('details.dho');
                
    //             $data['total_revenue'] = "$".number_format($totalrevenue, 0);
    //             $data['total_cost'] = "$".number_format($total_cost,0);
    //             $data['total_profit'] = "$".number_format(($totalrevenue - $total_cost),0);
    //             $data['total_mile'] = number_format($total_mile);
    //             $data['total_dho'] = number_format($total_dho);
    //             $data['fixed_cost'] = ""; 
    //             $data['fixed_cost_y'] = ""; 
    //             $data['loadexpense_y'] = "$".number_format($loadexpense,0); 
    //             $data['maintenance_y'] = "$".number_format($maintenance,0); 
                
    //             $t_miles = $total_mile + $total_dho;
    //             $data['total_rpm'] = ($t_miles)?"$".number_format(($totalrevenue/$t_miles),2):0;
    //             $data['cost_rpm']  = ($t_miles)?"$".number_format(($total_cost/$t_miles),2):0;
    //             $data['profit_rpm']  = ($t_miles)?"$".number_format((($totalrevenue - $total_cost)/$t_miles),2):0;
    //             $data['ratio'] = number_format($total_cost/$totalrevenue*100,2) . "%";
                
    //             return $data;
                
    // }
}
