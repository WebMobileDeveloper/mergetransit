<?php

namespace App\Http\Controllers\Sadmin;

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
       
        if(empty($request->detail_id)){
            $detail_id = 0;
        } else {
            $detail_id = $request->detail_id;
        }         
        
        $load_expense = Load_expense::join('details','details.id','=', 'load_expenses.detail_id')->
            join('contact_lists','details.contact_id','=', 'contact_lists.id')->
            select('load_expenses.*','contact_lists.d_company_name','details.po','details.put_date','details.del_date')->
            where('customer_id', $this->customer_id)->where('detail_id', '=', $detail_id)->get();
        
        $details = Detail:: join('contact_lists','details.contact_id','=', 'contact_lists.id')->
            join("drivers","drivers.id",'=',"details.driver_id")->
            select('contact_lists.d_company_name','details.po','details.put_date','details.del_date','details.id')->
            where('drivers.company_id', $this->customer_id)->get();
            
        return view('sadmin.loadexpense.index', compact('load_expense','detail_id', 'details'));
    }

    public function store(Request $request) {
        
        // try {
            
            $detail = Detail::findOrFail($request['detail_id']);
            $driver_id = $detail->driver_id;
            
            $driver = Driver::findOrFail($driver_id);
            $customer_id = $driver->company_id;
            

            $detail_id=$request['detail_id'];
            
            
            
            $tolls_sum=0;
            foreach ($request['tolls'] as $tolls) {
                $tolls_sum +=$tolls;
            }

            $lumper_sum=0;
            foreach ($request['lumper'] as $lumper) {
                $lumper_sum +=$lumper;
            }

            $accomerdations_sum=0;
            foreach ($request['accomerdations'] as $accomerdations) {
                $accomerdations_sum +=$accomerdations;
            }

            $other_sum=0;
            foreach ($request['other'] as $other) {
                $other_sum +=$other;
            }
            
            
            
            
            
            //$tolls_sum = empty($request['tolls'])?floatval(array_sum($request['tolls'])):0;
            //$lumper_sum = empty($request['lumper'])?floatval(array_sum($request['lumper'])):0;
            //$accomerdations_sum = empty($request['accomerdations'])?floatval(array_sum($request['accomerdations'])):0;
            //$other_sum = empty($request['other'])?floatval(array_sum($request['other'])):0;

            if($request['expense_id']==0){
                $loadexpense = new Load_expense();
                $loadexpense->date= $request['sel_date'];
                $loadexpense->detail_id= $request['detail_id'];
                $loadexpense->customer_id= $customer_id;
                $loadexpense->driver_id= $driver_id;
                $loadexpense->fuel= floatval($request['fuel']);
                $loadexpense->gallons= floatval($request['gallons']);
                $loadexpense->def= floatval($request['def']);
                $loadexpense->parking= floatval($request['parking']);
                $loadexpense->payroll= floatval($request['payroll']);
                
                $loadexpense->tolls= $tolls_sum;
                $loadexpense->tolls_txt= json_encode($request['tolls']);
                $loadexpense->lumper= $lumper_sum;
                $loadexpense->lumper_txt= json_encode($request['lumper']);
                $loadexpense->accomerdations= $accomerdations_sum;
                $loadexpense->accom_txt= json_encode($request['accomerdations']);
                $loadexpense->other= $other_sum;
                $loadexpense->other_txt= json_encode($request['other']);
                
                $loadexpense->file_path= '';
                $loadexpense->file_name= '';
                
                $loadexpense->total = floatval($request['fuel']) + floatval($request['def']) + floatval($request['parking']) + floatval($request['payroll']) +$tolls_sum + $lumper_sum + $accomerdations_sum + $other_sum;
                $loadexpense->save();
    
            }else{
                $loadexpense = Load_expense::find($request['expense_id']);
                $loadexpense->date= $request['sel_date'];
                $loadexpense->fuel= floatval($request['fuel']);
                $loadexpense->gallons= floatval($request['gallons']);
                $loadexpense->def= floatval($request['def']);
                $loadexpense->parking= floatval($request['parking']);
                $loadexpense->payroll= floatval($request['payroll']);

                $loadexpense->tolls= $tolls_sum;
                $loadexpense->tolls_txt= json_encode($request['tolls']);
                $loadexpense->lumper= $lumper_sum;
                $loadexpense->lumper_txt= json_encode($request['lumper']);
                $loadexpense->accomerdations= $accomerdations_sum;
                $loadexpense->accom_txt= json_encode($request['accomerdations']);
                $loadexpense->other= $other_sum;
                $loadexpense->other_txt= json_encode($request['other']);
                                
                $loadexpense->total = floatval($request['fuel']) + floatval($request['def']) + floatval($request['parking']) + floatval($request['payroll']) +$tolls_sum + $lumper_sum + $accomerdations_sum + $other_sum;
                $loadexpense->save();
            }
            $success = true;
        // } catch (\Exception $e) {
        //     $success = false;
        // }
    
        // if ($success) {
            return redirect("sadmin/loadexpense/".$detail_id);
        // } else {
        //     return response()->json(['data'=>$e], 200);
        // }
     
    }
    
    public function update(Request $request) {
         
        try {
            
            $loadexpense = Load_expense::find($request['id']);
            $loadexpense->date= $request['SelDate'];
            $loadexpense->fuel= $request['fuel'];
            $loadexpense->gallons= $request['gallons'];
            $loadexpense->def= $request['def'];
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
            
            $loadexpense->total = $request['fuel'] + $request['def'] + $request['parking'] + $request['tolls_sum'] + $request['lumper_sum'] + $request['accom_sum'] + $request['other_sum'];
            
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
          
            $loadexpense = Load_expense::findOrFail($request['expense_id']);
            
            $scan_check = $request['scan_f'];
            if($scan_check) {
    
                $filename ='Loadexpense_'. uniqid(). ".pdf" ;
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
