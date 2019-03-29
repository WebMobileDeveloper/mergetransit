<?php

namespace App\Http\Controllers\Sadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Driver;
use App\Contact_list;
use App\Customer;
use App\User;
use App\Detail; 
use App\Maintenance;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Mail;
use File;
use PDF;

class MaintenanceController extends Controller
{
    public $successStatus = 200;
    //

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
            $seldate = date('Y-m-d');
        } else {
            $seldate = $request->seldate;
        }         
        
        if(empty($request->id)){
            $editflag = 0;
            $main_edit=[];
        } else {
            $main_edit = Maintenance::where('id', $request->id)->get();
            $editflag = 1;
        }  

        $maintenance = Maintenance::where('customer_id', $this->customer_id)->where('date', 'like', '%'.$seldate.'%')->get();
            
        $drivers = Driver::join('users', 'users.id', '=', 'drivers.user_id')
        ->join('customers', 'customers.id', '=', 'drivers.company_id')
        ->select('drivers.id', 'drivers.company_id','drivers.employee_id', 'users.firstname', 'users.lastname', 'customers.company');
        $drivers=$drivers->where('drivers.company_id', $this->customer_id);
        $drivers = $drivers->where('users.is_active', 1);
        $drivers = $drivers->orderby('customers.company')->get();



        
            
        return view('sadmin.maintenance.index', compact('maintenance','seldate', 'drivers', 'editflag', 'main_edit'));
    }

    public function store(Request $request) {
        $seldate = "2018-10-08";
        try {
            if($request['maintenance_id']==0){
                $maintenance = new Maintenance();
                $maintenance->driver_id= $request['driver_id'];
                $maintenance->customer_id= $this->customer_id;
                $maintenance->date= $request['sel_date'];
                $maintenance->cost= $request['cost'];
                $maintenance->purpose= $request['purpose'];
                $maintenance->description= $request['description'];
                $maintenance->file_path= '';
                $maintenance->file_name= '';
                $maintenance->save();
            }else{
                $maintenance = Maintenance::find($request['maintenance_id']);
                $maintenance->driver_id= $request['driver_id'];
                $maintenance->date= $request['sel_date'];
                $maintenance->cost= $request['cost'];
                $maintenance->purpose= $request['purpose'];
                $maintenance->description= $request['description'];
                $maintenance->save();
            }

            $success = true;
        } catch (\Exception $e) {
            $success = false;
        }
    
        if ($success) {
            return redirect("sadmin/maintenance/".$seldate."/0");
        } else {
            return response()->json(['data'=>"Failed"], 200);
        }
     
    }
    
    public function update(Request $request) {
        
        try {
            $maintenance = Maintenance::find($request['id']);
          
            $maintenance->date= $request['SelDate'];
            $maintenance->cost= $request['cost'];
            $maintenance->purpose= $request['purpose'];
            $maintenance->description= $request['description'];
            $maintenance->file_path= '';
            $maintenance->file_name= '';
            
            $maintenance->save();
           
            $total_data = app('App\Http\Controllers\API\CostCenterController')->get_costinfo();
            $success = true;
        } catch (\Exception $e) {
            $success = false;
        }
    
        if ($success) {
            return response()->json(['data'=>'success', 'costinfo'=>$total_data], $this->successStatus);
        } else {
            return response()->json(['data'=>"Failed"], 200);
        }
     
    }
    
    public function upload_image (Request $request) {
        
         try {
          
            $maintenance = Maintenance::findOrFail($request['maintenance_id']);
            
            $scan_check = $request['scan_f'];
            if($scan_check) {
    
                $filename ='Maintenance_'. uniqid(). ".pdf" ;
                $filepath = public_path('files').'/'.$filename;
                $filepath_str = asset('/files/'.$filename);
                $image_base64 = base64_decode($request['imageupload']);
                
                $pdf=PDF::setOptions([
                    'logOutputFile' => storage_path('logs/log.htm'),
                    'tempDir' => storage_path('logs/')
                ])->loadView('scan_doc_pdf',['image' => 'data:image/jpg;base64,'.$request['imageupload']])->setPaper('a4')->save($filepath);
              
    
            } else {
                $filename = uniqid().'.jpg';
                $filepath_str = asset('/files/'.$filename);
                $filepath = public_path('files').'/'.$filename;
                $image_base64 = base64_decode($request['imageupload']);
                
                file_put_contents($filepath, $image_base64);
            }        
         	
         	
         	$files = $maintenance->file_path;
        	$names = $maintenance->file_name;       
            
            $maintenance->file_path = ($files=="")?$filepath_str:$files.",".$filepath_str ;
            $maintenance->file_name = ($names=="")?$filename:$names.",".$filename;
            
            $maintenance->save();
          
            $success = true;
        } catch (\Exception $e) {
            $success = false;
        }
    
        if ($success) {
            return response()->json(['data'=>'Success'], $this->successStatus);
        } else {
            return response()->json(['data'=>"Failed"], 200);
        }
    }
    
    
}
