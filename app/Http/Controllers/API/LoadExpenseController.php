<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Driver;
use App\Contact_list;
use App\Customer;
use App\User;
use App\Detail; 
use App\Load_expense;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Mail;
use File;
use PDF;

class LoadExpenseController extends Controller
{
    public $successStatus = 200;
    //
    public function index(){
       
    }

    public function store(Request $request) {
        
        try {
            
            $detail = Detail::findOrFail($request['detail_id']);
            $driver_id = $detail->driver_id;
            
            $driver = Driver::findOrFail($driver_id);
            $customer_id = $driver->company_id;
            
            $loadexpense = new Load_expense();
            $loadexpense->date= $request['SelDate'];
            $loadexpense->detail_id= $request['detail_id'];
            $loadexpense->customer_id= $customer_id;
            $loadexpense->driver_id= $driver_id;
            $loadexpense->fuel= floatval($request['fuel']);
            $loadexpense->payroll= floatval($request['payroll']);
            $loadexpense->gallons= floatval($request['gallons']);
            $loadexpense->def= floatval($request['def']);
            $loadexpense->parking= floatval($request['parking']);
            $loadexpense->tolls= floatval($request['tolls_sum']);
            $loadexpense->tolls_txt= $request['tolls'];
            $loadexpense->lumper= floatval($request['lumper_sum']);
            $loadexpense->lumper_txt= $request['lumper'];
            $loadexpense->accomerdations= floatval($request['accom_sum']);
            $loadexpense->accom_txt= $request['accom'];
            $loadexpense->other= floatval($request['other_sum']);
            $loadexpense->other_txt= $request['other'];
            
            $loadexpense->file_path= '';
            $loadexpense->file_name= '';
            
            $loadexpense->total = floatval($request['fuel']) + floatval($request['def']) + floatval($request['payroll']) + floatval($request['parking']) + floatval($request['tolls_sum']) + floatval($request['lumper_sum']) + floatval($request['accom_sum']) + floatval($request['other_sum']);
            
            $loadexpense->save();
            $last_id = $loadexpense->id;
            
            $total_data = app('App\Http\Controllers\API\CostCenterController')->get_costinfo();
            
            $success = true;
        } catch (\Exception $e) {
            $success = false;
        }
    
        if ($success) {
            return response()->json(['e_id'=>$last_id, 'costinfo'=>$total_data], $this->successStatus);
        } else {
            return response()->json(['data'=>"Failed"], 200);
        }
     
    }
    
    public function update(Request $request) {
         
        try {
            
            $loadexpense = Load_expense::find($request['id']);
            $loadexpense->date= $request['SelDate'];
            $loadexpense->fuel= $request['fuel'];
            $loadexpense->gallons= $request['gallons'];
            $loadexpense->def= $request['def'];
            $loadexpense->payroll= $request['payroll'];
            $loadexpense->parking= $request['parking'];
            $loadexpense->tolls= $request['tolls_sum'];
            $loadexpense->tolls_txt= $request['tolls'];
            $loadexpense->lumper= $request['lumper_sum'];
            $loadexpense->lumper_txt= $request['lumper'];
            $loadexpense->accomerdations= $request['accom_sum'];
            $loadexpense->accom_txt= $request['accom'];
            $loadexpense->other= $request['other_sum'];
            $loadexpense->other_txt= $request['other'];
            
            $loadexpense->file_path= '';
            $loadexpense->file_name= '';
            
            $loadexpense->total = $request['fuel'] + $request['def'] + $request['payroll'] + $request['parking'] + $request['tolls_sum'] + $request['lumper_sum'] + $request['accom_sum'] + $request['other_sum'];
            
            $loadexpense->save();
            
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

            $loadexpense = Load_expense::findOrFail($request['expense_id']);
            
            $scan_check = $request['scan_f'];
            if($scan_check) {
    
                $filename ='Loadexpense_'. uniqid(). ".pdf" ;
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
         	
         	
         	$files = $loadexpense->file_path;
        	$names = $loadexpense->file_name;       
            
            $loadexpense->file_path = ($files=="")?$filepath_str:$files.",".$filepath_str ;
            $loadexpense->file_name = ($names=="")?$filename:$names.",".$filename;
            
            $loadexpense->save();
          
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
