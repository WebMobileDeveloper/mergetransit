<?php

namespace App\Http\Controllers\Admin;

use App\Customer;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Stripe\Stripe;


class customerController extends Controller
{
    
    // public $organization_plan = 'plan_E5TM8YdmGQ1YMe';
    // public $optimization_plan = 'plan_E5TO4qaj2LgpbO';
    // public $test_plan = 'plan_E5TP7iK25QkVEu'; 
    
    //test
    //public $organization_plan = 'plan_E5m9iLfaiuByh6';
	//live
	public $organization_plan = 'plan_DzpMsfrw1aFHZF';
    public function __construct()
    {
    //    $stripe_key = 'sk_test_peMer0SN4Jutx7Q5UVG69fBX';   
       //$stripe_key = 'sk_test_OKw4L1Lvo8ZorGgbS8OgpPue';
	   $stripe_key = env("STRIPE_SECRET_KEY");
       Stripe::setApiKey($stripe_key);
       $this->middleware('auth');
    }
    public function index(Request $request)
    {
        $s = $request->s;
        $customers = Customer::join('users', 'users.email', '=', 'customers.email');
        $customers=$customers->select('customers.*', 'users.id as u_id','users.is_active');
        if(isset($s))$customers -> search($s); 
        $customers=$customers->orderBy('customers.company','asc') ; 
        $customers = $customers->paginate(10);

        
        return view('admin.customers.index',compact('customers','s'));
    }
    
    public function create()
    {
        return view('admin.customers.customerAdd');
    }

    public function store(Request $request)
    {
        $users = User::where('users.email', $request['email'])->get();
        if (count($users)>0) {
            return redirect()->back()->withInput($request->all())->with("status", " Email is already registered. pleaes check email again.");
        } else {

            DB::beginTransaction();
            try {
                $user = new User();
                $user->firstname= $request['firstname'];
                $user->lastname= $request['lastname'];
                $user->email= $request['email'];
                $user->phone= $request['phone'];
                $user->password= bcrypt('123456');
                $user->role= 4;
                $user->is_active= 1;
                $user->save();

                $customer= new Customer();
                $customer->user_id = $user->id;
                $customer->company= $request['company'];
                $customer->firstname= $request['firstname'];
                $customer->lastname= $request['lastname'];
                $customer->street= $request['street'];
                $customer->city= $request['city'];
                $customer->state= $request['state'];
                $customer->zipcode= $request['zipcode'];
                $customer->email=$request['email'];
                $customer->phone= $request['phone'];
                $customer->image_path= '';
                $customer->description= $request['desc'];
                $customer->manual_invoice = ($request->manual_invoice=="on")?1:0;    
                $customer->save();

                DB::commit();
                $success = true;
            } catch (\Exception $e) {
                $success = false;
                DB::rollback();
            }
        
            if ($success) {
                return redirect('admin/customers');
            }
        }

    }

    public function show($id)
    {
        return redirect('admin/customers');
    }

    public function edit($id)
    {
        $customer = Customer::find($id);
        return view('admin.customers.customerEdit',compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::find($id);
        $customer_email = $customer->email;

        
        if (trim($request['email']) != $customer_email) {

            $users = User::where('users.email', $request['email'])->get();
            if (count($users)>0) {
                return redirect()->back()->withInput(compact('customer'))->with("status", " Email is already registered. pleaes check email again.");
                die();
            }
        } 
        
        DB::beginTransaction();
        try {
            $customer->description = Input::get('desc');
            $customer->company = Input::get('company');
            $customer->firstname = Input::get('firstname');
            $customer->lastname = Input::get('lastname');
            $customer->street = Input::get('street');
            $customer->city = Input::get('city');
            $customer->state = Input::get('state');
            $customer->zipcode = Input::get('zipcode');
            $customer->email = Input::get('email');
            $customer->phone = Input::get('phone');          
            $customer->manual_invoice = ($request->manual_invoice=="on")?1:0;    
            $customer->save();

            $user = User::where('users.email',$customer_email)->get();            
            $user_id = $user[0]->id;

            $update_user = User::findOrFail($user_id);

            //dd($user);
            $update_user->firstname = Input::get('firstname');
            $update_user->lastname = Input::get('lastname');
            $update_user->email = Input::get('email');
            $update_user->phone = Input::get('phone');   
            $update_user->save();

            DB::commit();
            $success = true;
        } catch (\Exception $e) {
            $success = false;
            DB::rollback();
        }
    
        if ($success) {
            return redirect('admin/customers');
        }
           
    }

    public function destroy($id)
    {

        DB::beginTransaction();
        try {
            $cusotmer = Customer::find($id);
            $email = $cusotmer->email;
            $cusotmer->delete();

            $user = User::where('users.email','=',$email)->get();      
            $user[0]->delete();

            DB::commit();
            $success = true;
        }catch (\Exception $e) {
            $success = false;
            DB::rollback();
        }
    
        if ($success) {
            return redirect('admin/customers');
        }  
    }

    public function setactive(Request $request){        
        $user_id = $request->user_id;
        $user = User::findOrFail($user_id);
        $customer_id = $request->customer_id;

        $isActive = $request->isActive;
        $register_account = true;
        if ($isActive == 1) {

            
            //Check the member type
            $customer = Customer::findOrFail($customer_id);
            $member_type = $customer->member_type;

            if ($member_type == 2) {
                //integration Stripe
                try
                {
                    
					$name = 'Merget Transit - Organization';
					$plan_id = $this->organization_plan;

                    $user->newSubscription($name, $plan_id)->create($customer->card_token, [
                        'email' => $customer->email,
                    ]);
                    
                    $register_account = true;
                } catch(Exception $e) {
                    $register_account = false;
                }
            }

        } 

        if ($register_account) {
            
            $user->is_active = $isActive;
            
            if($user->save()) {
                $result['status']='success';
               
            }else{
                $result['status']='failed';
                $result['err'] = 'Activate is failed';
            }
        } else {
            $result['status']='failed';
            $result['err'] = 'Stripe integration error';
        }

        die (json_encode($result));
        
    }


    public function delete_file($id, $no){
        $customer = Customer::findOrFail($id);
        $files_arr = explode(",",$customer->image_path);
       
        
        unset($files_arr[$no]); $refile_arr = array_values($files_arr); 
      
        $customer->image_path = implode(',',$refile_arr);
        
        $save_status = $customer->save();
        if($save_status){
            
            die("ok");
        }
   }
}