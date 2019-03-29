<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Driver;
use App\Customer;
use App\User;
use App\Detail;
use App\Detail_addr;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $drivers = Driver::join('users', 'users.id', '=', 'drivers.user_id')
        ->join('customers', 'customers.id', '=', 'drivers.company_id')
        ->select('drivers.id', 'drivers.company_id','drivers.employee_id', 'users.firstname', 'users.lastname', 'customers.company');
        // if (Auth::user()->role==3) {
        //     $drivers = $drivers->where('drivers.employee_id', Auth::user()->id);
        // }
        $drivers = $drivers->orderby('customers.company')->get();

        return view('admin.reports.index', compact('drivers'));
    }

    public function generate(Request $request)
    {
        $driver_id = $request['driver_id'];
        $company = $request['company'];
        $po = $request['po'];
        $origin = $request['origin'];
        $destination = $request['destination'];
        
        $start_date = $request['startdate'];
        $end_date = $request['enddate'];

        //get all drivers
        $drivers = Driver::join('users', 'users.id', '=', 'drivers.user_id')
        ->join('customers', 'customers.id', '=', 'drivers.company_id')
        ->select('drivers.id', 'drivers.company_id', 'drivers.employee_id','users.firstname', 'users.lastname', 'customers.company');
        // if (Auth::user()->role==3) {
        //     $drivers = $drivers->where('drivers.employee_id', Auth::user()->id);
        // }
        $drivers = $drivers->orderby('customers.company')->get();
        
        
        //get reports data of current driver
        // var_dump($driver_id);
         $reports = Detail::join('contact_lists', 'details.contact_id', 'contact_lists.id')
            ->select('details.*', 'contact_lists.d_company_name as company')
            ->where('driver_id', $driver_id);
        if ($company!="") {
                    $reports = $reports->where('contact_lists.d_company_name', 'like', '%' . $company . '%');
        }
        if ($po!="") {
            $reports = $reports->where('po', 'like', '%' . $po . '%');
        }
        if ($origin!="") {
            $reports = $reports->where('origin', 'like', '%' . $origin . '%');
        }
        if ($destination!="") {
            $reports = $reports->where('destination', 'like', '%' . $destination . '%');
        }
        if ($start_date!="" ) {
            if($end_date!="")
                $reports = $reports->whereBetween('put_date', [$start_date, $end_date." 23:59:59"]);
            else{
                $reports = $reports->where('put_date', '>=', $start_date);
            }
            
        }
        // $reports = $reports->get();
        $reports = $reports->orderBy('details.updated_at','DESC')->get();

        //get sum value
        $total_revenue = 0;
        $total_mile = 0;
        $total_dho = 0;
        foreach ($reports as $report) {
            $total_revenue=$total_revenue + $report->revenue;
            $total_mile=$total_mile + $report->mile;
            $total_dho=$total_dho + $report->dho;
        }
        $total_rpm = ($total_mile==0)?0:$total_revenue / $total_mile;
        $total_dhrpm = ($total_mile==0)?0:$total_revenue / ($total_mile + $total_dho);

        
        // number_format($number, 2, '.', ',')

        //get current driver information
        $current_driver = Driver::findOrFail($driver_id);
        $user = User::findOrFail($current_driver->user_id);
        $company = Customer::findOrFail($current_driver->company_id);
        $employee = User::findOrFail($current_driver->employee_id);

        $infor = array(
            'driver_name'=>($current_driver->company_id == 1)?$user->firstname." ".$user->lastname:$company->company."(".$user->firstname.")",
            'from'=>$start_date,
            'to'=>$end_date,
            'employee'=>$employee->firstname." ".$employee->lastname,
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
            'po' => $request['po'],
            'origin' => $request['origin'],
            'destination' => $request['destination'],   
            
            'start_date' => $request['startdate'],
            'end_date' => $request['enddate']

        );

        return view('admin.reports.index', compact('drivers', 'reports','infor','res_data'));
    }
}
