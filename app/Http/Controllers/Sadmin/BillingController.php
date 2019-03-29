<?php

namespace App\Http\Controllers\Sadmin;

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
use PDF;

class BillingController extends Controller
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
    public function index(Request $request)
    {

        if(Auth::user() == NULL) {
            return redirect('sadmin');
        }

        $customer_email = Auth::user()->email;
        $login_user_id = Auth::user()->id;

        $s = $request->s;
        
        $billings = Detail::join('drivers', 'details.driver_id', '=', 'drivers.id');
                       
        $billings=$billings->select('details.*', 'drivers.user_id');
       
        $billings=$billings->where('drivers.company_id', $this->customer_id);   
        $billings=$billings->where('details.invoice_created','<>','');   
        if(isset($s))$billings -> search($s);   
        
        $billings=$billings->orderBy('details.created_at','desc')->paginate(10); 
     
        return view('sadmin.billing.index', compact('billings','s','customer_email'));
    }

    public function set_paymentmark(Request $request) {
        $id = $request->id;
        $detail = Detail::find($id);
        $detail->paid_status = 1;
        $detail->save();
        $result['status'] = 'success';
        die(json_encode($result));
    }

    public function create_invoice($id){
        $detail_id = $id;
        $detail = Detail::join('contact_lists as c','details.contact_id','c.id')->
            select('details.*','c.d_company_name','c.address1','c.city','c.state','c.zipcode')->where('details.id', $detail_id)->get();
        $driver = Driver::join('details', 'details.driver_id','=', 'drivers.id')  
                    ->join('contact_lists','details.contact_id','contact_lists.id')                  
                    ->join('users','users.id','drivers.user_id')
                    ->where('details.id',$detail_id)->get();

        $driver = $driver[0];
      
        $customer = Customer::find($this->customer_id);

        return view('sadmin.billing.invoice_template', compact('detail_id','detail','driver','customer'));

    }

    public function generate_invoice(Request $request) {

        $detail_id = $request['detail_id'];
        $activity = $request['activity'];
        $amount  = $request['sp_rate'];
        $charge_array = array();

        for($i=0; $i< count($activity); $i++) {
           
            $item['text'] = $activity[$i];
            $item['rate'] = $amount[$i];
            array_push($charge_array,$item);
        }

        $invoice_details =  $this->arrayToObject($charge_array);



        //return response()->json(['add_charge'=>$add_charge[0]->text], $this->successStatus);
        
        $drivers = Driver::join('details', 'details.driver_id','=', 'drivers.id')  
                    ->join('contact_lists','details.contact_id','contact_lists.id')                  
                    ->join('users','users.id','drivers.user_id')
                    ->where('details.id',$detail_id)->get();
       
        // $contact = Detail::where('id', $drivers[0]->contact_id)->get();
        
        $customers = Customer::where('id', $drivers[0]->company_id)->get();
        $filename ='Invoice_'. uniqid(). ".pdf" ;
        $filepath = public_path('files').'/'.$filename;
        
        // $pdf=PDF::loadView('driver_invoice_pdf',['drivers' => $drivers,  'customers' => $customers, 'invoice_details' => $invoice_details])->setPaper('a4')->save($filepath);
        $pdf=PDF::setOptions([
            'logOutputFile' => storage_path('logs/log.htm'),
            'tempDir' => storage_path('logs/')
        ])->loadView('driver_invoice_pdf',['drivers' => $drivers,  'customers' => $customers, 'invoice_details' => $invoice_details])->setPaper('a4')->save($filepath);
      
      
     	$filepath_str = asset('/files/'.$filename); 
     	$detail = Detail::findOrFail($detail_id);
     
     	$files = $detail->upload;
    	$names = $detail->filename;       
        
            $detail->upload = ($files=="")?$filepath_str:$files.",".$filepath_str ;
            $detail->filename = ($names=="")?$filename:$names.",".$filename;
            $detail->invoice_created = date("Y-m-d");
        
        $detail->save();

        $result['status'] = 'ok';
        die(json_encode($result));
       
    }

    private function array_to_obj($array, &$obj)
    {
      foreach ($array as $key => $value)
      {
        if (is_array($value))
        {
        $obj->$key = new \stdClass();
        $this->array_to_obj($value, $obj->$key);
        }
        else
        {
          $obj->$key = $value;
        }
      }
    return $obj;
    }
  
    private function arrayToObject($array)
    {
        $object= new \stdClass();
        return $this->array_to_obj($array,$object);
    }

    




    public function send_invoice(Request $request){
        $detail_id = $request->detail_id;
        ///////
        $from = Auth::user();
        $to = $request->to;
        $subject = $request->subject;
        $content = $request->message;
        $attach = json_decode($request->attach);
        
        $message_arr = json_decode($content);
      
        $data = array(
            'from'    => $from,
            'to'      => $to,
            'subject' => $subject,
            'content' => $message_arr,
            'attach'  => $attach
        );
      
        
        $mail_status = Mail::send('sadmin.invoice.invoice_mail', $data,function($message) use($data){
            $message->to($data['to'])->subject($data['subject']);
            $message->from($data['from']->email, $data['from']->firstname." " .$data['from']->lastname);
            $message->replyTo($data['from']->email, $data['from']->firstname." " .$data['from']->lastname);
            foreach($data['attach'] as $filePath){
                $message->attach($filePath);
            }
                       
        });
       
        
        if(count(Mail::failures()) > 0){
            $result['msg'] = 'Failed to send invoice email, please try again.';
            $result['status'] = "fail";
        }else{

            $result['msg'] = 'Sent the invoice email succesfully.';
            $result['status'] = "success";
        }

        die(json_encode($result));
    }

    
/*
    public function set_payment(Request $request){
        $invoice_id = $request->id;
        $invoice = Invoice::find($invoice_id);
        if($invoice->send_status == 0){
            $result['msg'] = "The invoice is not sent yet.\n Please confirm before.";
            $result['status']="fail";
        }else{
            $invoice->paid_status=1;
            if($invoice->save()){
                $result['status']="success";
            }else{
                $result['status']="fail";
                $result['msg'] = "Failed the save.";
            }            
        }
        die(json_encode($result));
    }


    //invoice delete 
    public function destroy($id)
    {
        $invoice_detail = Invoice_detail::where('inv_id','=',$id)->get();
        foreach ($invoice_detail as $recode) {
            $recode -> delete();
        }       
        
        

        $invoice_special = Invoice_special::where('inv_id',$id)->get();
        foreach ($invoice_special as $recode) {
            $recode -> delete();
        }

        $invoice = Invoice::find($id);
        $invoice->delete();

        // return redirect('admin/invoice');
        return back();
    }
    */
}
