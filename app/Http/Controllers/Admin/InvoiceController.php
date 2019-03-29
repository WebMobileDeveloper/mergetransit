<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Driver;
use App\Customer;
use App\User;
use App\Detail;
use App\Invoice;
use App\Invoice_detail;
use App\Invoice_special;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Mail;

class InvoiceController extends Controller
{
    public function __construct()
    {
       $this->middleware('auth');
    }
    public function index()
    {

        // get the customers who can receive the invoice.
        $customers = Customer::orderBy("id")->get();
       
// dd($customer);
        if(date('D')!='Sun')
        {    
         //take the last sunday
          $staticstart = date('Y-m-d',strtotime('last Sunday'));            
        }else{
            $staticstart = date('Y-m-d');   
        }        
        //always next saturday
        if(date('D')!='Sat')
        {
            $staticfinish = date('Y-m-d',strtotime('next Saturday'));
        }else{        
                $staticfinish = date('Y-m-d');
        }
        $date_array = array(
            'weekstart'=>$staticstart,
            'weekend'=>$staticfinish
        );

        

        return view('admin.invoice.index', compact('customers','date_array','dispatch_detail'));
    }

    public function create_invoice($id){
        $customer = Customer::find($id);
        // dd($customer);

        $drivers = Driver::where("company_id",$id)->get();
        
        $dispatch_detail = array();
        //get this weed date
        if(date('D')!='Sun')
        {    
         //take the last sunday
          $staticstart = date('Y-m-d',strtotime('last Sunday'));            
        }else{
            $staticstart = date('Y-m-d');   
        }        
        //always next saturday
        if(date('D')!='Sat')
        {
            $staticfinish = date('Y-m-d',strtotime('next Saturday'));
        }else{        
                $staticfinish = date('Y-m-d');
        }
      
        //
        foreach($drivers as $driver){
            $driver_detail = User::where('id',$driver->user_id)->get();

            $details = Detail::where('driver_id',$driver->id)->whereBetween('put_date', [$staticstart, $staticfinish." 23:59:59"])->get();

            foreach($details as $detail){
                if  ( $detail->revenue >= 1000) {
                    $rate = 50;
                } elseif ( $detail->revenue >= 250 && $detail->revenue < 1000) {
                    $rate = 30;
                } else {
                    $rate = 0;
                }
                $dispatch_detail[]=array(
                    'trans_company'=>$detail->company,
                    'pu_date'=>$detail->put_date,
                    'driver_id'=>$detail->driver_id,
                    'driver_name'=>$driver_detail[0]->firstname,
                    'revenue'=>$detail->revenue,
                    'due_date'=>$staticfinish,
                    'rate'=>($detail->revenue>=1000)?50:30
                );
            }

        }

        //get the latest invoice_number
        $invoice = DB::table('invoices')->where('invoice_no', DB::raw("(select max(`invoice_no`) from invoices)"))->get();
       
        $info = array(
            'due_date'=>$staticfinish
        );
        // dd($dispatch_detail);
        return view('admin.invoice.create_invoice',compact('customer','dispatch_detail','invoice','info'));
    }

    public function store_invoice(Request $request){
  
   
        $invoice = new Invoice();
        $invoice->invoice_no = $request->invoice_no;
        $invoice->customer_id = $request->customer_id;
        $invoice->create_id =  $request->customer_id."_".$request->due_date;
        $invoice->due_date = $request->due_date;
        $invoice->bill_amount = $request->bill_amount;
        $invoice_save = $invoice->save();
        $last_id= $invoice->id;


        if($invoice_save) {
            
             $revenue_arr = $request->revenue;
             $trans_company_arr = $request->trans_company;
             $driver_name_arr = $request->driver_name;
             $pu_date_arr = $request->pu_date;
             $rate_arr = $request->rate;
            
             for($i=0;$i<$request->total_count;$i++){
                $invoice_detail = new Invoice_detail();
                // var_dump($trans_company_arr[$i]);
                $invoice_detail->invoice_id = $request->invoice_no;
                $invoice_detail->inv_id = $last_id;
                $invoice_detail->revenue = $revenue_arr[$i];
                $invoice_detail->tra_company = $trans_company_arr[$i];
                $invoice_detail->driver_name = $driver_name_arr[$i];
                $invoice_detail->pu_date = $pu_date_arr[$i];
                $invoice_detail->billing_amount = $rate_arr[$i];
                $invoice_detail->save();    

             }

             if($request->special_count!=0){
                $sp_activity_arr = $request->activity;
                $sp_qty_arr = $request->sp_qty;
                $sp_rate_arr = $request->sp_rate;
                $sp_amount_arr = $request->sp_amount;
                for($i=0;$i<$request->special_count;$i++){
                    $invoice_special = new Invoice_special();
                    $invoice_special->invoice_id = $request->invoice_no;
                    $invoice_special->inv_id = $last_id;
                    $invoice_special->activity = $sp_activity_arr[$i];
                    $invoice_special->qty = $sp_qty_arr[$i];
                    $invoice_special->rate = $sp_rate_arr[$i];
                    $invoice_special->amount = $sp_amount_arr[$i];
                    $invoice_special->save();
                }
             }
             $result["status"]="OK";
             die(json_encode ($result));


        }
    }

    public function send_invoice(Request $request){
        $customer_id = $request->customer_id;
        $invoice_no = $request->invoice_no;
        $due_date = $request->due_date;

        $customer = Customer::find($customer_id);

        $invoice = Invoice::where('invoice_no',$invoice_no)->get();
        $invoice_id = $invoice[0]->id;
        $invoice_detail = Invoice_detail::where('invoice_id',$invoice_no)->get();
        $invoice_special = Invoice_special::where('invoice_id',$invoice_no)->get();

       
        $data = array(
            'customer' => $customer,
            'invoice' => $invoice,
            'invoice_detail' => $invoice_detail,
            'invoice_special' => $invoice_special
        );


        $mail_status = Mail::send('admin.invoice.invoice_mail', $data,function($message) use($data){
            $message->to($data['customer']->email,$data['customer']->firstname." ".$data['customer']->lastname)->subject('Invoice');
            $message->from('billing@mergetransit.com','Mergetransit');
            $message->replyTo('billing@mergetransit.com','Mergetransit');
        });
       
        
        if(count(Mail::failures()) > 0){
            $result['msg'] = 'Failed to send invoice email, please try again.';
            $result['status'] = "fail";
        }else{

            $iv = Invoice::find($invoice_id);
            $iv->send_status = 1;
            $iv->save();
            $result['msg'] = 'Sent the invoice email succesfully.';
            $result['status'] = "success";
        }

        die(json_encode($result));
    }

    //view detail page
    public function view_detail($id){
        $customer = Customer::find($id);        
        $invoices = Invoice::where('customer_id',$id)->orderBy("due_date","desc")->get();
        return view('admin.invoice.detail', compact('invoices','customer'));
    }

    public function view_invoice($id){
        $invoice = Invoice::find($id);
        $invoice_no = $invoice->invoice_no;
        $customer_id = $invoice->customer_id;
        $customer =Customer::find($customer_id);
        $invoice_details = Invoice_detail::where('invoice_id',$invoice_no)->get();
        $invoice_specials = Invoice_special::where('invoice_id',$invoice_no)->get();
        return view('admin.invoice.invoice_view',compact('invoice','invoice_details','invoice_specials','customer'));
    }

    public function invoice_edit($id){
        $invoice = Invoice::find($id);
        $invoice_no = $invoice->invoice_no;
        
        $staticfinish = $invoice->due_date;
        $staticstart = date('Y-m-d', strtotime('-6 days', strtotime($staticfinish)));
// dd($staticstart);
        $customer_id = $invoice->customer_id;
        $customer =Customer::find($customer_id);
        // $invoice_details = Invoice_detail::where('invoice_id',$invoice_no)->get();
        $invoice_specials = Invoice_special::where('invoice_id',$invoice_no)->get();

        $drivers = Driver::where("company_id",$customer_id)->get();
        
        $dispatch_detail = array();
        //
        foreach($drivers as $driver){
            $driver_detail = User::where('id',$driver->user_id)->get();

            $details = Detail::where('driver_id',$driver->id)->whereBetween('put_date', [$staticstart, $staticfinish])->get();

            foreach($details as $detail){
                $dispatch_detail[]=array(
                    'trans_company'=>$detail->company,
                    'pu_date'=>$detail->put_date,
                    'driver_id'=>$detail->driver_id,
                    'driver_name'=>$driver_detail[0]->firstname,
                    'revenue'=>$detail->revenue,
                    'due_date'=>$staticfinish,
                    'rate'=>($detail->revenue>=1000)?50:30
                );
            }

        }

        //get the latest invoice_number
        // $invoice = DB::table('invoices')->where('invoice_no', DB::raw("(select max(`invoice_no`) from invoices)"))->get();
       

        return view('admin.invoice.invoice_edit',compact('invoice','dispatch_detail','invoice_specials','customer'));
    }

    public function update_invoice(Request $request){
        // dd("d");
        // var_dump ($request->invoice_no);
        $invoice_id = $request->invoice_id;
        $invoice = Invoice::find($invoice_id);

        $invoice->invoice_no = $request->invoice_no;
        $invoice->customer_id = $request->customer_id;
        $invoice->create_id =  $request->customer_id."_".$request->due_date;
        $invoice->due_date = $request->due_date;
        $invoice->bill_amount = $request->bill_amount;
        $invoice_save = $invoice->save();

        if($invoice_save) {
            if($request->total_count!=0){

                $temp_detail = Invoice_detail::where('invoice_id',$request->invoice_no)->get();
                foreach($temp_detail as $temp){
                    $temp->delete();
                }

                $revenue_arr = $request->revenue;
                $trans_company_arr = $request->trans_company;
                $driver_name_arr = $request->driver_name;
                $pu_date_arr = $request->pu_date;
                $rate_arr = $request->rate;

                
               
                // var_dump($request->invoice_no);exit;
                
                for($i=0;$i<$request->total_count;$i++){
                    $invoice_detail = new Invoice_detail();
                    // var_dump($trans_company_arr[$i]);
                    $invoice_detail->invoice_id = $request->invoice_no;
                    $invoice_detail->inv_id = $invoice->id;
                    $invoice_detail->revenue = $revenue_arr[$i];
                    $invoice_detail->tra_company = $trans_company_arr[$i];
                    $invoice_detail->driver_name = $driver_name_arr[$i];
                    $invoice_detail->pu_date = $pu_date_arr[$i];
                    $invoice_detail->billing_amount = $rate_arr[$i];
                    $invoice_detail->save();    

                }
            }
             if($request->special_count!=0){

                $temp_special = Invoice_special::where('invoice_id',$request->invoice_no)->get();
                if(count($temp_special)>0){
                    foreach($temp_special as $temp1){
                        $temp1->delete();
                    }
                }
                

                $sp_activity_arr = $request->activity;
                $sp_qty_arr = $request->sp_qty;
                $sp_rate_arr = $request->sp_rate;
                $sp_amount_arr = $request->sp_amount;
                for($i=0;$i<$request->special_count;$i++){
                    $invoice_special = new Invoice_special();
                    $invoice_special->invoice_id = $request->invoice_no;
                    $invoice_special->inv_id = $invoice->id;
                    $invoice_special->activity = $sp_activity_arr[$i];
                    $invoice_special->qty = $sp_qty_arr[$i];
                    $invoice_special->rate = $sp_rate_arr[$i];
                    $invoice_special->amount = $sp_amount_arr[$i];
                    $invoice_special->save();
                }
             }
             $result["status"]="OK";
             die(json_encode ($result));
        }
    }

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
}
