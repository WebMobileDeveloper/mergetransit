<?php

namespace App\Http\Controllers;

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


class ReportController extends Controller
{
    public function __construct()
    {
       $this->middleware('auth');
    }

    public function index(){       

        $user_id = Auth::user()->id;
        $user = User::find($user_id);
        $role = $user->role;
        
        $drivers = Driver::join('customers', 'customers.id', '=', 'drivers.company_id')
        ->join('users', 'users.id', '=', 'drivers.user_id')
        ->select('drivers.id', 'drivers.company_id', 'drivers.employee_id','users.firstname', 'users.lastname', 'customers.company');
        
        if($role == 0) {
            $drivers = $drivers->where('users.email', Auth::user()->email);
        } else {
            $drivers = $drivers->where('customers.email', Auth::user()->email);
        }
        
        $drivers = $drivers->get();
        
        $res_data =array();
        
        return view('report.index',compact('drivers','res_data'));
    }
    public function generate(Request $request){

        $user_id = Auth::user()->id;
        $user = User::find($user_id);
        $role = $user->role;
        // $driver= Driver::where('user_id',$user_id)->get();
        $driver = $request['driver_id'];
       
        $company = $request['company'];
        $origin = $request['origin'];
        $destination = $request['destination'];
        $start_date = $request['startdate'];$from = date("Y-m-d", strtotime($start_date));
        $end_date = $request['enddate'];$to = date("Y-m-d", strtotime($end_date));
       
        $drivers = Driver::join('customers', 'customers.id', '=', 'drivers.company_id')
        ->join('users', 'users.id', '=', 'drivers.user_id')
        ->select('drivers.id', 'drivers.company_id', 'drivers.employee_id','users.firstname', 'users.lastname', 'customers.company');
        if($role == 0) {
            $drivers = $drivers->where('users.email', Auth::user()->email);
        } else {
            $drivers = $drivers->where('customers.email', Auth::user()->email);
        }
        
        $drivers = $drivers->get();
        
        // get reports data of current driver
        //var_dump($driver_id);
        if($driver!=""){
            $reports = DB::table('details');
       
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
               if ($start_date!="" ) {
                    if($end_date!="")
                        $reports = $reports->whereBetween('put_date', [$from, $to." 23:59:59"]);
                    else{
                        $reports = $reports->where('put_date', '>=', $from);
                    }
                    
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
            $total_dhrpm = ($total_dho==0)?0:$total_revenue / ($total_mile + $total_dho);

        
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
                'total_revenue'=>number_format($total_revenue, 2, '.', ','),
                'total_mile'=>number_format($total_mile, 2, '.', ','),
                'total_dho'=>number_format($total_dho, 2, '.', ','),
                'total_rpm'=>number_format($total_rpm, 2, '.', ','),
                'total_dhrpm'=>number_format($total_dhrpm, 2, '.', ','),
                'driver_id'=>$driver_id
            );
            
            $res_data = array(
                'driver_id' => $request['driver_id'],
                'company' => $request['company'],
                'origin' => $request['origin'],
                'destination' => $request['destination'],   
                
                'start_date' => $request['startdate'],
                'end_date' => $request['enddate']
    
            );
      
        
            return view('report.index', compact('drivers','reports','infor','res_data'));
        }else{
           
            return view('report.index');
        }
        
    }
}
