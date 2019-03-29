<?php

namespace App\Http\Controllers\API;

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
    public function index(){
       
    }

    public function store(Request $request) {
        
        try {
            $maintenance = new Maintenance();
          
            $maintenance->driver_id= $request['driverID'];
            $maintenance->customer_id= $request['customerID'];
            $maintenance->date= $request['SelDate'];
            $maintenance->cost= $request['cost'];
            $maintenance->purpose= $request['purpose'];
            $maintenance->description= $request['description'];
            $maintenance->file_path= '';
            $maintenance->file_name= '';
            
            $maintenance->save();
            $last_id = $maintenance->id;
            
            
            $total_data = app('App\Http\Controllers\API\CostCenterController')->get_costinfo();
            $success = true;
        } catch (\Exception $e) {
            $success = false;
        }
    
        if ($success) {
            return response()->json(['m_id'=>$last_id, 'costinfo'=>$total_data], $this->successStatus);
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
            $folder = Date('Y') . "/" .Date('m');
            // var_dump("====",$folder); exit;
            $path = public_path('files') . "/" . $folder;

            if ( is_dir(public_path('files') ."/". Date('Y')) === false ) {
                $old = umask(0);
                mkdir(public_path('files') ."/". Date('Y'), 0777);
                umask($old);
            }
            if ( is_dir($path) === false ) {
                $old = umask(0);
                mkdir($path, 0777);
                umask($old);
            }
            $scan_check = $request['scan_f'];
            if($scan_check) {
    
                $filename ='Maintenance_'. uniqid(). ".pdf" ;
                $filepath = $path.'/'.$filename;
                $filepath_str = asset('/files/'.$folder . '/'.$filename);
                $image_base64 = base64_decode($request['imageupload']);
                
                $pdf=PDF::setOptions([
                    'logOutputFile' => storage_path('logs/log.htm'),
                    'tempDir' => storage_path('logs/')
                ])->loadView('scan_doc_pdf',['image' => 'data:image/jpg;base64,'.$request['imageupload']])->setPaper('a4')->save($filepath);
              
    
            } else {
                $filename = uniqid().'.jpg';
                $filepath_str = asset('/files/'.$folder . '/'.$filename);
                $filepath = $path.'/'.$filename;
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
