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
use Stripe\Stripe;


class AccountController extends Controller
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
        $user = Auth::user();
        $customers = Customer::find($this->customer_id);
    
            $accont['userId'] = $user->id;
            $accont['name'] = $user->name;
            $accont['email'] = $user->email;
            $accont['company'] = $customers->company;
            $accont['customerID'] = $this->customer_id;
            $accont['firstname'] = $customers->firstname;
            $accont['lastname'] = $customers->lastname;
            $accont['phone'] = $customers->phone;
            $accont['street'] = $customers->street;
            $accont['city'] = $customers->city;
            $accont['state'] = $customers->state;
            $accont['zipcode'] = $customers->zipcode;
            $accont['description'] = $customers->description;

        return view('sadmin.account.account', compact('accont'));
    }
    public function update(Request $request)
    {
        $user = Auth::user();
        $users = User::findOrFail($user->id);
        $users->firstname=$request['firstname'];
        $users->lastname=$request['lastname'];
        $users->phone=$request['phone'];
        $users->save();

        $customers = Customer::find($this->customer_id);
        $customers->firstname=$request['firstname'];
        $customers->lastname=$request['lastname'];
        $customers->phone=$request['phone'];
        $customers->company = $request['company'];
        $customers->street = $request['street'];
        $customers->city = $request['city'];
        $customers->state = $request['state'];
        $customers->zipcode = $request['zipcode'];
        $customers->save();

        return redirect("sadmin/account");
    }


    public function service() {
      
        
        $customer = Customer::find($this->customer_id);
        $service['member_type'] = $customer->member_type;
        $service['card_token'] = $customer->card_token;

        // dd($service);

        // if ($customer->card_token != "Free" && $customer->member_type !=1) {
            $user = User::find($customer->user_id);
          
            $service['brand'] = $user->card_brand;          
            $service['last4'] = $user->card_last_four;
            
        // } else {
            // $service['brand'] = "";          
            // $service['last4'] = "";
        // }
        
        return view('sadmin.account.service', compact('service'));
    }

    public function service_update(Request $request) {
        $stripe_key = env("STRIPE_SECRET_KEY");
        Stripe::setApiKey($stripe_key);
        try {

            $service = $request['service'];
            $customer= Customer::find($this->customer_id);            
            $currentMemberType = $customer->member_type;
            $customer->member_type = $request['service'];

            switch ($service) {
                case 1:
                    break;
                case 2:
                    if ($currentMemberType != 2) {
                        $customer->upgrade_date = date('Y-m-d');  
                    }
                    if ($request['card_change'] == 1) {
                        // dd($request['stripe_token']);   
                        $customer->card_token = $request['stripe_token'];  
                        $stripe_user = User::find(Auth::user()->id);
                        // dd($stripe_user);
                        if (!empty($stripe_user->stripe_id)) {
                            $stripe_user->updateCard($request['stripe_token']);
                            $stripe_user->save();   
                        } else {
                            $stripe_user->createAsStripeCustomer($request['stripe_token'], [
                                'email' => Auth::user()->email,
                                "description" => "Mergetransit"
                            ]);
                            $stripe_user->save();
                                            
                            $stripe_user->charge(9900, [
                                'description' => 'Merget Transit - Organization',
                            ]);
                            
                            
                        }    
                                
                    } 
                    break;
                case 3:

                    $stripe_user = User::find(Auth::user()->id);
                    if ($request['card_change'] == 1) {
                        $customer->card_token = $request['stripe_token'];  
                    
                        if (!empty($stripe_user->stripe_id)) {
                                $stripe_user->updateCard($request['stripe_token']);
                                $stripe_user->save();   
                           
                            
                        } else {
                            $stripe_user->createAsStripeCustomer($request['stripe_token'], [
                                'email' => Auth::user()->email,
                                "description" => "Mergetransit"
                            ]);
                            $stripe_user->save();                            
                        }                                
                    } 
                    if($currentMemberType != 3) {
                        // $stripe_user->charge(12500, [
                        //     'description' => 'Merget Transit - Optimization',
                        // ]);
                    }
                    break;
                default: 
                    break;

            }

            $customer->save();

        } catch(\Exception $e) {
            $success = false;
            DB::rollback();
         
            $result['status'] = false;
            $result['message'] = $e->getMessage();
         
            return redirect()->back()->withInput($request->all())->with("status", $e->getMessage());
           
        }

        return redirect("sadmin/service");
    }

}