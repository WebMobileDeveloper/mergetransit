<?php

namespace App\Http\Controllers\API;

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
use File;
use Mail;


class AddDriversController extends Controller
{
    public $successStatus = 200;
    public function __construct()
    {
       //$this->middleware('auth');
       
    }

    public function adddrivers(Request $request)
    {
        
        if($request['email']!=''){
            $users = User::where('users.email',$request['email'])
            ->get();
            
            if(count($users)>0)
                return response()->json(['data'=>'EmailTrue'], $this->successStatus);
        }else{
            $request['email'] =' ';
        }

        $default_password =  bcrypt($this->randomPassword());

        DB::beginTransaction();
        try {
            $customer = Customer::find($request['customer']);

            $user = new User();
            $user->parent_id = $customer->user_id;
            $user->firstname= $request['firstname'];
            $user->lastname= $request['lastname'];
            $user->email= $request['email'];
            $user->phone= $request['phone'];
            $user->password= $default_password;
            $user->is_active = 1;
            $user->save();

            $user_id = $user->id;

            $driver= new Driver();
            $driver->user_id = $user_id;
            $driver->company_id= $request['customer'];
            $driver->mc_number= $request['mc_number'];
            $driver->equipment= $request['equipment'];
            $driver->max_weight= $request['max_weight'];
            $driver->truck= $request['truck'];
            $driver->trailer= $request['trailer'];
            $driver->employee_id='';
            $driver->save();

            DB::commit();
            $success = true;
        }catch (\Exception $e) {
            $success = false;
            DB::rollback();
        }
    
        if ($success) {
            if ($request['email'] != ' ') {
                $data = array(
                    'username' =>  $request['firstname']." ".$request['lastname'],                  
                    'email' => $request['email'],
                    'phone' => $request['phone'],
                    'password' => $this->randomPassword()
                );    
        
                $mail_status = Mail::send('email_template.driver_password_email', $data,function($message) use($data){
                    $message->to($data['email'],$data['username'])->subject('Your Temporary Password');
                    $message->from(env('ADMIN_EMAIL'), 'Mergetransit');
                    $message->replyTo(env('ADMIN_EMAIL'), 'Mergetransit');
                });   
            }
            return response()->json(['data'=>'success'], $this->successStatus);
        } else {
            return response()->json(['data'=>'fail'], $this->successStatus);
        }

    }

    public function randomPassword() {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
    
}
