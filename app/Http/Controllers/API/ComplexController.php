<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Complex;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PDF;


class ComplexController extends Controller
{
    public $successStatus = 200;
    public function __construct()
    {
       //$this->middleware('auth');
    }

    public function index(){
        $drivers = Driver::join('users', 'users.id', '=', 'drivers.user_id')
        ->join('customers', 'customers.id', '=', 'drivers.company_id')
        
        ->select('drivers.id', 'drivers.company_id','drivers.employee_id', 'users.firstname', 'users.lastname', 'customers.company')
        ->where('users.email',Auth::user()->email)->get();
        
        // return view('report.index',compact('drivers'));
    }

    public function generate(Request $request){
       
        $user_id = Auth::user()->id;
        $user_email = Auth::user()->email;
        
        
        $driver = $request['driver_id'];
       
        $company = $request['company'];
        $origin = $request['origin'];
        $po = $request['po'];
        $destination = $request['destination'];
        $start_date = $request['startdate'];
        $end_date = $request['enddate'];
       
        // get all drivers
        $drivers = Driver::join('customers', 'customers.id', '=', 'drivers.company_id')
        ->join('users', 'users.id', '=', 'drivers.user_id')
        ->select('drivers.id', 'drivers.company_id', 'drivers.employee_id','users.firstname', 'users.lastname', 'customers.company')
        ->where('customers.email', Auth::user()->email)->get();
        
        // get reports data of current driver
        //var_dump($driver_id);
        if($driver!=""){
            $reports = Detail::join('contact_lists', 'details.contact_id', 'contact_lists.id')
            ->select('details.*', 'contact_lists.d_company_name as company')
            ->where('driver_id', $driver);
        
           
            if($request){
                if ($company!="") {
                    $reports = $reports->where('contact_lists.d_company_name', 'like', '%' . $company . '%');
                }
                if ($origin!="") {
                    $reports = $reports->where('origin', 'like', '%' . $origin . '%');
                }
                if ($destination!="") {
                    $reports = $reports->where('destination', 'like', '%' . $destination . '%');
                }
                if ($po!="") {
                    $reports = $reports->where('po', 'like', '%' . $po . '%');
                }
                if ($start_date!="") {
                    if($end_date!="")
                        $reports = $reports->whereBetween('put_date', [$start_date, $end_date]);
                    else
                        $reports = $reports->where('put_date', '>=', $start_date);
                }
                
            }
            $reports = $reports->get();

            //get sum value
            $total_revenue = 0;
            $total_mile = 0;
            $total_dho = 0;
            foreach ($reports as $report) {
                $total_revenue=$total_revenue + $report->revenue;
                $total_mile=$total_mile + $report->mile;
                $total_dho=$total_dho + $report->dho;
            }
            $total_rpm = ($total_dho==0)?0:$total_revenue / $total_mile;
            $total_dhrpm = ($total_dho==0)?0:$total_revenue / ($total_mile  + $total_dho);

        
        // number_format($number, 2, '.', ',')

        //get current driver information
        
            $current_driver = Driver::findOrFail($driver);
            $user = User::findOrFail($current_driver->user_id);
            $company = Customer::findOrFail($current_driver->company_id);
            $driver_name = ($current_driver->company_id == 1)?$user->firstname." ".$user->lastname:$company->company."(".$user->firstname.")";
            $driver_id = $driver;
            
            $infor = array(
                'driver_name'=>$driver_name,
                'from'=>$start_date,
                'to'=>$end_date,
                'total_revenue'=>"$".number_format($total_revenue, 2, '.', ','),
                'total_mile'=>number_format($total_mile),
                'total_dho'=>number_format($total_dho),
                'total_rpm'=>"$".number_format($total_dhrpm, 2, '.', ','),
                // 'total_dhrpm'=>number_format($total_dhrpm, 2, '.', ','),
                'driver_id'=>$driver_id
            );
       
        
            $data = array(
                'drivers'=>$drivers,
                'infor'=>$infor,
                'reports'=>$reports,
            );
            return response()->json(['data'=>$data], $this->successStatus);
        }else{
           
            return response()->json(['data'=>"empty"], $this->successStatus);
        }
        
    }

    public function get_zipcode(Request $request){
        
        $zipcode = $request['zipcode_valid'];
        $complex = Complex::select('zipcode','lat', 'lon', 'display_name', 'street_address', 'photo')->where('zipcode',$zipcode)->get();
        if($complex)
            return response()->json(['data'=>$complex], $this->successStatus);
        else
            return response()->json(['data'=>false], $this->successStatus);
   }
    
}
