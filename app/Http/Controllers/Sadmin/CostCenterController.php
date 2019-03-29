<?php

namespace App\Http\Controllers\Sadmin;

use App\Customer;
use App\User;
use App\Detail; 
use App\Driver; 
use App\Maintenance;
use App\Load_expense;
use App\Fixed_cost;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Mail;
use File;
use PDF;

class CostCenterController extends Controller
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
  
    public function index(){
        $drivers = Driver::join('users', 'users.id', '=', 'drivers.user_id')
        ->join('customers', 'customers.id', '=', 'drivers.company_id')
        ->select('drivers.id', 'drivers.company_id','drivers.employee_id', 'users.firstname', 'users.lastname', 'customers.company');
        $drivers=$drivers->where('drivers.company_id', $this->customer_id);
        $drivers = $drivers->where('users.is_active', 1);      
        $drivers = $drivers->orderby('customers.company')->get();
        return view('sadmin.costreports.index', compact('drivers'));
    }
    public function generate(Request $request)
    {
        $drivers = Driver::join('users', 'users.id', '=', 'drivers.user_id')
        ->join('customers', 'customers.id', '=', 'drivers.company_id')
        ->select('drivers.id', 'drivers.company_id','drivers.employee_id', 'users.firstname', 'users.lastname', 'customers.company');
        $drivers=$drivers->where('drivers.company_id', $this->customer_id);
        $drivers = $drivers->where('users.is_active', 1);      
        $drivers = $drivers->orderby('customers.company')->get();


        $startdate  = $request['startdate'];
        $enddate    = $request['enddate'];
        
        $from = $this->setstartDate($startdate);
        $to   = $this->setendDate($enddate);

        $driverID = $request['driver_id'];

        if ($driverID == '') {
            $where_m = "WHERE customer_id=". $this->customer_id;
            $where_l = "WHERE load_expenses.customer_id=". $this->customer_id;
            
            $costinfo = $this->customer_costinfo( $from, $to,  null);
        } else {
            $where_m = " WHERE driver_id=". $driverID;
            $where_l = " WHERE load_expenses.driver_id=". $driverID;
            $costinfo = $this->driver_costinfo( $from, $to,  $driverID);
        }
        $where2 = " AND date>='".$from."' AND date<='".$to."'" ;

        $sqlQuery = "SELECT 
                        id, 
                        date, 
                        driver_id,
                        purpose as s_Purpose, 
                        cost as s_Cost,
                        cost as total,
                        description as s_Description,
                        file_path,
                        file_name
                       
                    FROM maintenances "
                    .$where_m.$where2;
        $result_maintenance = DB::select($sqlQuery);
         

        $sqlQuery_load = "SELECT 
                load_expenses.id, 
                load_expenses.date, 
                load_expenses.driver_id,
                details.po as s_PO,
                contact_lists.d_company_name as s_Company,
                load_expenses.fuel as s_Fuel, 
                load_expenses.gallons as s_Gallons,
                load_expenses.def as s_DEF,
                load_expenses.parking as s_Parking,
                load_expenses.tolls as s_Tolls,
                load_expenses.tolls_txt as toll_txt,
                load_expenses.lumper as s_Lumper,
                load_expenses.lumper_txt,
                load_expenses.accomerdations as s_Hotel,
                load_expenses.accom_txt,
                load_expenses.other as s_Other,
                load_expenses.total as s_Total,
                load_expenses.total,
                load_expenses.other_txt,
                load_expenses.file_name,
                load_expenses.file_path,
                load_expenses.detail_id
            FROM load_expenses 
            INNER JOIN details ON details.id = load_expenses.detail_id
            INNER JOIN contact_lists ON contact_lists.id = details.contact_id "
            .$where_l.$where2;          
        $result_load = DB::select($sqlQuery_load);

       $res_data = array(
        'driver_id' => $request['driver_id'],
        // 'company' => $request['company'],
        // 'origin' => $request['origin'],
        // 'destination' => $request['destination'],   
        
        'start_date' => $request['startdate'],
        'end_date' => $request['enddate']

    );

        return view('sadmin.costreports.index', compact('drivers','result_maintenance', 'result_load','costinfo','res_data'));
    }

    public function customer_costinfo( $startdate = null, $enddate = null , $driverID = null){
       
        $from = $this->setstartDate($startdate);
        $to   = $this->setendDate($enddate);
        
        $user = Auth::user();
        
        $reports = Driver::join('details', 'details.driver_id', '=', 'drivers.id')
                    ->join('customers', 'customers.id', '=', 'drivers.company_id')
                    ->where('customers.id', $this->customer_id)->whereBetween('details.put_date', array($from, $to));  
            
        //get cost total
        $fixed_cost = Fixed_cost::select('total')
            ->where('customer_id', $this->customer_id)
            ->where('date', 'like', '%' . date("Y-m") . '%')
            ->get();
            
        $fixed_cost_y = Fixed_cost::where('customer_id', $this->customer_id)
            ->whereBetween('date', array($from, $to))
            ->sum('total');
            
        $loadexpense = Load_expense::where('customer_id', $this->customer_id)
            ->whereBetween('date', array($from, $to))
            ->sum('total');
            
        $maintenance = Maintenance::where('customer_id', $this->customer_id)
            ->whereBetween('date', array($from, $to))
            ->sum('cost'); 
            
        $month = Date('m');
        $total_cost = $fixed_cost_y + $loadexpense+ $maintenance;
        $totalrevenue = $reports->sum('details.revenue');
        $total_mile = $reports->sum('details.mile');
        $total_dho = $reports->sum('details.dho');
        
        $data['total_revenue'] = "$".number_format($totalrevenue, 2);
        $data['total_cost'] = "$".number_format($total_cost,2);
        $data['total_profit'] = "$".number_format(($totalrevenue - $total_cost),2);
        $data['total_mile'] = number_format($total_mile);
        $data['total_dho'] = number_format($total_dho);
        $data['fixed_cost'] = (count($fixed_cost)==0)?"$0.00":"$".number_format($fixed_cost[0]->total,2); 
        $data['fixed_cost_y'] = "$".number_format($fixed_cost_y,2); 
        $data['loadexpense_y'] = "$".number_format($loadexpense,2); 
        $data['maintenance_y'] = "$".number_format($maintenance,2); 
        
        $t_miles = $total_mile + $total_dho;
        $data['total_rpm'] = ($t_miles)?"$".number_format(($totalrevenue/$t_miles),2):0;
        $data['cost_rpm']  = ($t_miles)?"$".number_format(($total_cost/$t_miles),2):0;
        $data['profit_rpm']  = ($t_miles)?"$".number_format((($totalrevenue - $total_cost)/$t_miles),2):0;
        $data['ratio'] = ($totalrevenue == 0)?"0%":number_format($total_cost/$totalrevenue*100,2) . "%";
            
        return $data;
    }
    public function driver_costinfo( $startdate = null, $enddate = null , $driver_ID = null){
        $from = $this->setstartDate($startdate);
        $to   = $this->setendDate($enddate);
       
        $user = Auth::user();
     
                
        $reports = Driver::join('details', 'details.driver_id', '=', 'drivers.id')
            ->where('details.driver_id',$driver_ID)->whereBetween('details.put_date', array($from, $to));               
        
        
        //get cost total
        
        $loadexpense = Load_expense::where('driver_id',$driver_ID)->whereBetween('date', array($from, $to))->sum('total');
        $maintenance = Maintenance::where('driver_id', $driver_ID)->whereBetween('date', array($from, $to))->sum('cost'); //->whereYear('date', '=', date("Y"))
            
        $month = Date('m');
        $total_cost = $loadexpense+ $maintenance;
        $totalrevenue = $reports->sum('details.revenue');
        $total_mile = $reports->sum('details.mile');
        $total_dho = $reports->sum('details.dho');
        
        $data['total_revenue'] = "$".number_format($totalrevenue, 2);
        $data['total_cost'] = "$".number_format($total_cost,2);
        $data['total_profit'] = "$".number_format(($totalrevenue - $total_cost),2);
        $data['total_mile'] = number_format($total_mile);
        $data['total_dho'] = number_format($total_dho);
        $data['fixed_cost'] = 0; 
        $data['fixed_cost_y'] = 0; 
        $data['loadexpense_y'] = "$".number_format($loadexpense,2); 
        $data['maintenance_y'] = "$".number_format($maintenance,2); 
        
        $t_miles = $total_mile + $total_dho;
        $data['total_rpm'] = ($t_miles)?"$".number_format(($totalrevenue/$t_miles),2):0;
        $data['cost_rpm']  = ($t_miles)?"$".number_format(($total_cost/$t_miles),2):0;
        $data['profit_rpm']  = ($t_miles)?"$".number_format((($totalrevenue - $total_cost)/$t_miles),2):0;
        $data['ratio'] = ($totalrevenue)?number_format($total_cost/$totalrevenue*100,2) . "%": '0%';
        
        return $data;
        
    }
    public function setstartDate($date) {
        if (empty($date)) {
            $date = date('Y-m-d', strtotime('-1 year', strtotime(date('Y-m-d'))));
            
        }
         
        return $date;
    }
    
    public function setendDate($date) {
        if (empty($date)) {
             $date = date("Y-m-d");
        }
           
        return $date;
    }
    
    
}