<?php

namespace App\Http\Controllers\Admin;
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

class DriverController extends Controller
{
    
    public function __construct()
    {
       $this->middleware('auth');
    }
    public function index(Request $request)
    {
       
        $s = $request->s;
        $assigned = $request->assigned;
        $drivers=Driver::join('users', 'drivers.user_id', '=', 'users.id');
        $drivers=$drivers->join('customers', 'drivers.company_id', '=', 'customers.id');
        $drivers=$drivers->select('drivers.*', 'users.firstname','users.lastname','customers.company',
                                    'users.email','users.phone','users.is_active','users.role');
        $drivers=$drivers->where('users.role','0');
        if(isset($s))$drivers -> search($s);   
        if(isset($assigned) && $assigned == 0)$drivers=$drivers->where('drivers.employee_id','');
        $drivers=$drivers->orderBy("customers.company",'asc')->paginate(10);

        return view('admin.drivers.index',compact('drivers','s','assigned'));
    }
    
    public function create()
    {
        $customers = Customer::orderBy("company",'asc')->get();
        $employees = User::where('role','!=',0)->orderBy("users.firstname",'asc')->get();
        return view('admin.drivers.driverAdd',compact('customers','employees'));
    }

    public function store(Request $request)
    {
     
        if( $request['email'] != '' ){
            $users = User::where('users.email',$request['email'])->get();            
            
            if(count($users)>0){
                return redirect()->back()->withInput($request->all())->with("status", " Email is already registered. pleaes check email again.");
            }
        }else{
            $request['email'] =' ';
        }

        DB::beginTransaction();
        try {
            //get parent id
            $customer = Customer::find($request['customer']);

            $user = new User();
            $user->parent_id = $customer->user_id;
            $user->firstname= $request['firstname'];
            $user->lastname= $request['lastname'];
            $user->email= $request['email'];
            $user->phone= $request['phone'];
            $user->password= bcrypt("123456");
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
            return redirect('admin/drivers');
        }
    }

    public function show($id)
    {
        return redirect('admin/drivers');
    }

    public function edit($id)
    {       
        $driver =  Driver::join('users','drivers.user_id','=','users.id')
                        ->select('drivers.*','users.firstname','users.lastname',
                        'users.email','users.phone','users.is_active','users.role')
                        ->where('drivers.id','=',$id)->get();
                        
        $customers = Customer::orderBy("company",'asc')->get();
        $employees = User::where('role','!=',0)->where('role','!=',4)->orderBy("users.firstname",'asc')->get();
        return view('admin.drivers.driverEdit',compact('driver','customers','employees'));
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
            $user->parent_id= $customer->user_id;
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
            return redirect('admin/drivers');
        }       
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
            return redirect('admin/drivers');
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
        $drivers=$drivers->orderBy("customers.company",'asc')->paginate(15);

        return view('admin.drivers.employee_show',compact('drivers'));
    }

    public function email_check () {
        
    }
}