<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Driver;
use App\Contact_list;
use App\Customer;
use App\User;
use App\Detail;
use App\Invoice;
use App\Invoice_detail;
use App\Invoice_special;
use File;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Mail;
use PDF;

class InvoiceController extends Controller
{
    public $successStatus = 200;
    //
    public function index(){
        // if(Auth::user()->role==0){
            $customers = Customer::join('users', 'users.email', '=', 'customers.email')
                    ->select('customers.id')
                    ->where('users.email', Auth::user()->email)->get();
                    
            $customer_id = $customers[0]->id;
            
            
            $invoices = Invoice::where('customer_id',$customer_id)->where('send_status',1)->orderBy('due_date','desc')->get();
            //return view('billing.index',compact('invoices'));
            
            $result_data = [];
            foreach ($invoices as $invoice) {
                $invoice_no = $invoice->invoice_no;
                $invoice_details = Invoice_detail::where('invoice_id',$invoice_no)->get();
                $invoice_specials = Invoice_special::where('invoice_id',$invoice_no)->get();
                $result['invoice'] = $invoice;
                $result['invoice_details']=$invoice_details;
                $result['invoice_specials']=$invoice_specials;
                
                $result_data[] = $result;
                
            }
            
            $data = array(
                'invoices'=>$result_data
            );
            
            
            return response()->json(['data'=>$data], $this->successStatus);
    }

    public function invoice_create(Request $request) {

        $detail_id = $request['id'];
        $invoice_details = json_decode($request['add_charge']);

        //return response()->json(['add_charge'=>$add_charge[0]->text], $this->successStatus);
        
        $drivers = Driver::join('details', 'details.driver_id','=', 'drivers.id')  
                    ->join('contact_lists','details.contact_id','contact_lists.id')                  
                    ->join('users','users.id','drivers.user_id')
                    ->where('details.id',$detail_id)->get();
       
        // $contact = Detail::where('id', $drivers[0]->contact_id)->get();
        
        $customers = Customer::where('id', $drivers[0]->company_id)->get();

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
        $filename ='Invoice_'. uniqid(). ".pdf" ;
        $filepath = $path.'/'.$filename;
        
        // $pdf=PDF::loadView('driver_invoice_pdf',['drivers' => $drivers,  'customers' => $customers, 'invoice_details' => $invoice_details])->setPaper('a4')->save($filepath);
        $pdf=PDF::setOptions([
            'logOutputFile' => storage_path('logs/log.htm'),
            'tempDir' => storage_path('logs/')
        ])->loadView('driver_invoice_pdf',['drivers' => $drivers,  'customers' => $customers, 'invoice_details' => $invoice_details])->setPaper('a4')->save($filepath);
      
      
     	$filepath_str = asset('/files/'.$folder . '/'.$filename); 
     	$detail = Detail::findOrFail($detail_id);
     
     	$files = $detail->upload;
    	$names = $detail->filename;       
        
            $detail->upload = ($files=="")?$filepath_str:$files.",".$filepath_str ;
            $detail->filename = ($names=="")?$filename:$names.",".$filename;
            $detail->invoice_created = date("Y-m-d");
        
        $detail->save();
       
        return response()->json(['data'=>"Success"], $this->successStatus);
     	
    
        // return $pdf->download('event.pdf');
        //return $pdf->setpaper('a4')->download($filename.".".$ext);
        // return view('invoice_pdf',compact('invoice','customer','invoice_details','invoice_specials'));
    }
    
    public function payment_made(Request $request) {
        $detail_id = $request['detail_id'];
    	$detail = Detail::findOrFail($detail_id);
    	$detail->paid_status = 1;
        $detail->save();
        
        return response()->json(['data'=>"Success"], $this->successStatus);
    	
    }

}
