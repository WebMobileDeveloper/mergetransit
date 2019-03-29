<?php

namespace App\Http\Controllers\Sadmin;
use App\Http\Controllers\Controller;
use App\Driver;
use App\Customer;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Mail;

class DriverController extends Controller
{
    
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
    public function index(Request $request)
    {
       
        $s = $request->s;
        $assigned = $request->assigned;
        $drivers=Driver::join('users', 'drivers.user_id', '=', 'users.id');
        $drivers=$drivers->join('customers', 'drivers.company_id', '=', 'customers.id');
        $drivers=$drivers->select('drivers.*', 'users.firstname','users.lastname','customers.company',
                                    'users.email','users.phone','users.is_active','users.role');
        $drivers=$drivers->where('customers.id',$this->customer_id);
        $drivers=$drivers->where('users.role','0');
        if(isset($s))$drivers -> search($s);   
        if(isset($assigned) && $assigned == 0)$drivers=$drivers->where('drivers.employee_id','');
        $drivers=$drivers->orderBy("customers.company",'asc')->paginate(10);

        return view('sadmin.drivers.index',compact('drivers','s','assigned'));
    }
    
    public function create()
    {
        $customers = Customer::orderBy("company",'asc')->where('id', $this->customer_id)->get();
        return view('sadmin.drivers.driverAdd',compact('customers'));
    }

    public function store(Request $request)
    {
     
        if( $request['email'] != '' ){
            if(filter_var($request['email'], FILTER_VALIDATE_EMAIL)){
                $users = User::where('users.email',$request['email'])->get();            
            
                if(count($users)>0){
                    return redirect()->back()->withInput($request->all())->with("status", " Email is already registered. pleaes check email again.");
                }
            }else{
                return redirect()->back()->withInput($request->all())->with("status", " Error Email Validation!");
            }
            
        }else{
            $request['email'] =' ';
        }
        $default_password =  bcrypt($this->randomPassword());
        DB::beginTransaction();
        try {
            $customer = Customer::find($request['customer']);

            $user = new User();
            $user->parent_id= $customer->user_id;
            $user->firstname= $request['firstname'];
            $user->lastname= $request['lastname'];
            $user->email= $request['email'];
            $user->phone= $request['phone'];
            $user->is_active = 1;            
            $user->password= $default_password;
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
        
            $employees= $request['employee'];  
            if( count($employees) != 0) {
                $em_id = '';
                foreach($employees as $employee)
                {
                    $em_id = ($em_id == '')?$employee:$em_id.",".$employee;
                }
                $driver->employee_id= $em_id;
            } else {
                $driver->employee_id = '';
            }
            $driver->save();

            $driver_id = $driver->id;

            DB::commit();
            $success = true;
        } catch (\Exception $e) {
            $success = false;
            DB::rollback();
        }
    
        if ($success) {
            //send email to driver
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
            return redirect('sadmin/drivers');
        }      


    }

    public function show($id)
    {
        return redirect('sadmin/drivers');
    }

    public function edit($id)
    {       
        $driver =  Driver::join('users','drivers.user_id','=','users.id')                       
                        ->select('drivers.*','users.firstname','users.lastname',
                        'users.email','users.phone','users.is_active','users.role')                        
                        ->where('drivers.id','=',$id)->get();
                        
        $customers = Customer::where('customers.id',$this->customer_id)->orderBy("company",'asc')->get();
        $employees = User::where('role','!=',0)->where('role','!=',4)->orderBy("users.firstname",'asc')->get();
        return view('sadmin.drivers.driverEdit',compact('driver','customers','employees'));
    }

    public function update(Request $request, $id)
    {


        $driver = Driver::findOrFail($id);

        $user_id = $driver->user_id;
        $user = User::findOrFail($user_id);
        $user_email = $user->email;

        if( $request['email'] != '' ){
            if( trim($request['email']) != $user_email ) {
                $current_users = User::where('users.email', $request['email'])->get();
                if (count($current_users)>0) {
                    return redirect()->back()->withInput(compact('customer'))->with("status", " Email is already registered. pleaes check email again.");
                    die();
                }
            }
        } else {
            $request['email'] =' ';
        }
        
        DB::beginTransaction();
        try {
            $customer = Customer::find($request['customer']);

            $driver->company_id= $request['customer'];
            $driver->mc_number= $request['mc_number'];
            $driver->equipment= $request['equipment'];
            $driver->max_weight= $request['max_weight'];
            $driver->truck= $request['truck'];
            $driver->trailer= $request['trailer'];
            $employees= $request['employee'];       
            $em_id = '';
            if( count($employees) != 0) {
            foreach($employees as $employee)
                {
                    $em_id = ($em_id == '')?$employee:$em_id.",".$employee;
                }
                $driver->employee_id= $em_id;
            } else {
                $driver->employee_id= '';
            }
            $driver->save();

            $user_id = $driver->user_id;
            $user = User::findOrFail($user_id);
            $user->parent_id = $customer->user_id;
            $user->firstname= $request['firstname'];
            $user->lastname= $request['lastname'];
            $user->email= $request['email'];
            $user->phone= $request['phone'];
            $user->save();

            DB::commit();
            $success = true;
        } catch (\Exception $e) {
            $success = false;
            DB::rollback();
        }
    
        if ($success) {
            return redirect('sadmin/drivers');
        }   
        
        
       
        return redirect('sadmin/drivers');
    }

    public function destroy($id)
    {
        
        DB::beginTransaction();
        try {
            $driver = Driver::find($id);
            $user_id = $driver->user_id;
            $driver->delete();

            $user = User::find($user_id);
            $user->delete();

            DB::commit();
            $success = true;
        }catch (\Exception $e) {
            $success = false;
            DB::rollback();
        }
    
        if ($success) {
            return redirect('sadmin/drivers');
        }  
    }

    public function setactive(Request $request){        
        $id = $request->id;

        $isActive = $request->isActive;

        $driver = Driver::findOrFail($id);
        $user = User::findOrFail($driver->user_id);
        $user->is_active = $isActive;
        
        if($user->save()) {
            die (json_encode("ok"));
        }else{
            die (json_encode("fail"));
        }
    }

    public function employee_show(){
        $user_id = Auth::user()->id;
        $role = Auth::user()->role;

        $drivers = DB::table('drivers');
        $drivers=$drivers->join('users', 'drivers.user_id', '=', 'users.id');
        $drivers=$drivers->join('customers', 'drivers.company_id', '=', 'customers.id');
        $drivers=$drivers->select('drivers.*', 'users.firstname','users.lastname','customers.company',
                                    'users.email','users.phone','users.is_active','users.role');
        $drivers=$drivers->where('users.role','0');

        $drivers=$drivers->whereRaw('FIND_IN_SET(?,drivers.employee_id)', [$user_id]);    //////////////// Special query :)
        $drivers=$drivers->orderBy("drivers.id")->paginate(15);

        return view('sadmin.drivers.employee_show',compact('drivers'));
    }

    public function email_check () {
        
    }

    public function find_parent () {
        // $users = User::where('role', 0)->get();
       
        // $drivers=Driver::join('users', 'drivers.user_id', '=', 'users.id');
        // $drivers=$drivers->join('customers', 'drivers.company_id', '=', 'customers.id');
        // $drivers=$drivers->select('drivers.*', 'users.id as user_id', 'customers.email as email_addr');
        // $drivers=$drivers->where('users.role','0')->get();

        
        // foreach( $drivers as $driver){           
        //     // $customer = Customer::where('id',$company_id)->get();
        //     $customers = User::where('email', $driver->email_addr)->get();

        //     $customer_user = Customer::find($driver->company_id);
        //     $customer_user->user_id = $customers[0]->id;
        //     $success2 = $customer_user->save();

        //     // $driver_user = User::find($driver->user_id);
        //     // $driver_user->parent_id = $customers[0]->id;
        //     // $success = $driver_user->save();

        //     var_dump("user-".$driver->user_id, "====".$driver->company_id, "====".$driver->email_addr, "====".$customers[0]->id, "==".$success, "==".$success2); echo "<br>";
        // }

        $users = User::join('customers','users.email', '=', 'customers.email')
        ->select('users.id as u_id', 'users.email', 'customers.id as c_id')
        ->where('users.role', 4)->get();


        foreach ($users  as $user) {
            $customer = Customer::find($user->c_id);

            $customer->user_id = $user->u_id;
            $customer->save();

            var_dump("user-".$user->u_id, "====".$user->c_id, "====".$user->email); echo "<br>";
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