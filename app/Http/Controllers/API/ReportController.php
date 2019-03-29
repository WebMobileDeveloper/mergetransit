<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Driver;
use App\Customer;
use App\User;
use App\Detail;
use App\Detail_addr;
use App\Maintenance;
use App\Fixed_cost;
use App\Load_expense;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use File;
use PDF;
use Stripe\Stripe;


class ReportController extends Controller
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
       
        $user_id     = Auth::user()->id;
        $user_email  = Auth::user()->email;
        
        $driver_id   = $request['driver_id'];
       
        $company     = $request['company'];
        $origin      = $request['origin'];
        $po          = $request['po'];
        $destination = $request['destination'];
        $start_date  = $this->setstartDate($request['startdate']);
        $end_date    = $this->setendDate($request['enddate']);
       
        // get all drivers
       
        
        // get reports data of current driver
        
        if (Auth::user()->role == 4){
             $customer = Customer::join('users', 'users.email', '=', 'customers.email')
                        ->select('customers.id')
                        ->where('users.email', Auth::user()->email)->get();
                $customer_id = $customer[0]->id;
            
            if($driver_id != ""){
                
                $reports = Detail::join('contact_lists', 'details.contact_id', 'contact_lists.id')
                ->select('details.*', 'contact_lists.d_company_name as company')
                ->where('driver_id', $driver_id);
                
                 $drivers = Driver::join('customers', 'customers.id', '=', 'drivers.company_id')
                ->join('users', 'users.id', '=', 'drivers.user_id')
                ->select('drivers.id', 'drivers.company_id', 'drivers.employee_id','users.firstname', 'users.lastname', 'customers.company')
                ->where('drivers.id', $driver_id)->get();
                
            } else {
                 $drivers = Driver::join('customers', 'customers.id', '=', 'drivers.company_id')
                ->join('users', 'users.id', '=', 'drivers.user_id')
                ->select('drivers.id', 'drivers.company_id', 'drivers.employee_id','users.firstname', 'users.lastname', 'customers.company')
                ->where('customers.email', Auth::user()->email)->get();
                
               
                
                $reports = Detail::join('contact_lists', 'details.contact_id', 'contact_lists.id')
                ->join('drivers','drivers.id', '=', 'details.driver_id')
                ->join('customers', 'customers.id','=', 'drivers.company_id')
                ->select('details.*', 'contact_lists.d_company_name as company')
                ->where('customers.id', $customer_id);
            }
        } else {
            $reports = Detail::join('contact_lists', 'details.contact_id', 'contact_lists.id')
                    ->select('details.*', 'contact_lists.d_company_name as company')
                    ->where('driver_id', $driver_id);
                    
             $drivers = Driver::join('customers', 'customers.id', '=', 'drivers.company_id')
            ->join('users', 'users.id', '=', 'drivers.user_id')
            ->select('drivers.id', 'drivers.company_id', 'drivers.employee_id','users.firstname', 'users.lastname', 'customers.company')
            ->where('drivers.id', $driver_id)->get();
        }
           
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


        if (Auth::user()->role == 4){
            
            //get cost total
            $fixed_cost = Fixed_cost::select('total')
                ->where('customer_id', $customer[0]->id)
                ->where('date', 'like', '%' . date("Y-m") . '%')
                ->get();
                
            $fixed_cost_y = Fixed_cost::where('customer_id', $customer[0]->id)
                ->whereBetween('date', array($start_date, $end_date))
                ->sum('total');
                
            $loadexpense = Load_expense::where('customer_id', $customer[0]->id)
                ->whereBetween('date', array($start_date, $end_date))
                ->sum('total');
                
            $maintenance = Maintenance::where('customer_id', $customer[0]->id)
                ->whereBetween('date', array($start_date, $end_date))
                ->sum('cost'); 
                
            $total_cost = $fixed_cost_y + $loadexpense+ $maintenance;
        } else {
            $loadexpense = Load_expense::where('driver_id',$driver_id)->whereBetween('date', array($start_date, $end_date))->sum('total');
            $maintenance = Maintenance::where('driver_id', $driver_id)->whereBetween('date', array($start_date, $end_date))->sum('cost'); 
            $total_cost = $loadexpense+ $maintenance;
        }
       
        
        
        $t_miles = $total_mile + $total_dho;
      
        //$info_data = app('App\Http\Controllers\API\CostCenterController')->get_costinfo();
        
        $infor = array(
            'driver_name'    =>'',
            'from'           =>$start_date,
            'to'             =>$end_date,
            'total_revenue'  =>"$".number_format($total_revenue, 2, '.', ','),
            'total_mile'     =>number_format($total_mile),
            'total_dho'      =>number_format($total_dho),
            'total_rpm'      =>"$".number_format($total_dhrpm, 2, '.', ','),
            'total_cost'     =>"$".number_format($total_cost,2, '.', ',' ),
            'total_profit'   => "$".number_format(($total_revenue - $total_cost),2),
            'total_rpm'      => ($t_miles)?"$".number_format(($total_revenue/$t_miles),2):0,
            'cost_rpm'       => ($t_miles)?"$".number_format(($total_cost/$t_miles),2):0,
            'profit_rpm'     => ($t_miles)?"$".number_format((($total_revenue - $total_cost)/$t_miles),2):0,
            'ratio'          => ($total_revenue)?number_format($total_cost/$total_revenue*100,2) . "%": "0%",
            
            // 'total_dhrpm'=>number_format($total_dhrpm, 2, '.', ','),
            'driver_id'=>$driver_id
        );
   
    
        $data = array(
            'drivers'=>$drivers,
            'infor'=>$infor,
            'reports'=>$reports,
        );
        return response()->json(['data'=>$data], $this->successStatus);
       
        
    }
    public function calendar(Request $request){

        $user_id = Auth::user()->id;
        // get all drivers
        $drivers = Driver::join('customers', 'customers.id', '=', 'drivers.company_id')
        ->join('users', 'users.id', '=', 'drivers.user_id')
        ->select('drivers.id', 'drivers.company_id', 'drivers.employee_id','users.firstname', 'users.lastname', 'customers.company')
        ->where('customers.email', Auth::user()->email)->get();
        
            
            $customersID = $request['customersID'];
            
            $reports = Detail::join('drivers', 'drivers.id', 'details.driver_id')
                        ->join('customers','customers.id','drivers.company_id')
                        ->join('contact_lists', 'details.contact_id', 'contact_lists.id')
                        ->select('details.driver_id', 'details.id', 'contact_lists.d_company_name as company', 'details.contact', 'details.po' ,'details.put_date' ,'details.del_date' ,'details.origin' ,'details.destination' ,'details.weight' ,'details.dho' ,'details.rpm', 'details.dh_rpm', 'details.revenue', 'details.mile')
                        ->where('customers.id', $customersID);
        
        
            
            $po = $request['po'];
            $start_date = $request['startdate'];
            $end_date   = $request['enddate'];
            $driverid   = $request['driverid'];
            if ($po!="") {
                $reports = $reports->where('po', 'like', '%' . $po . '%');
            }
            if ($driverid!="") {
                $reports = $reports->where('driver_id', $driverid);
            }
            if ($start_date!="") {
                if($end_date!="")
                    $reports = $reports->whereBetween('put_date', [$start_date, $end_date]);
                else
                    $reports = $reports->where('put_date', '>=', $start_date);
            }
            
                $reports = $reports->get();
                return response()->json(['data'=>$reports], $this->successStatus);
        
    }

    public function getDetailData(Request $request){

            $driverid = $request['driverid'];
            $today = date('Y-m-d');
            $reports = Detail::where('driver_id', $driverid);
            $reports = $reports->where('put_date', '<=', $today)->where('del_date', '>=', $today);
            $reports = $reports->get();
            if(count($reports)){
                $detail_origin = Detail_addr::select('*')-> where('detail_id',$reports[0]->id)->where('zip_type','origin')->orderBy('order_index')->get();
                $detail_dest = Detail_addr::select('*')-> where('detail_id',$reports[0]->id)->where('zip_type','destination')->orderBy('order_index')->get();                        
                $detaladdress = [];
                foreach ($detail_origin as $itme) {
                    array_push($detaladdress, $itme);
                }
        
                foreach ($detail_dest as $itme) {
                    array_push($detaladdress, $itme);
                }
                return response()->json(['address'=>$detaladdress, 'status' => 'true'], $this->successStatus);
            }
                
            else
                return response()->json(['status' => 'false'], $this->successStatus);
        
    }
    
    public function fileupload(Request $request)
    {
        
        $detail = Detail::findOrFail($request['detail_id']);
        $scan_check = $request['scan_f'];
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
        
        if($scan_check) {
            
            $filename ='ScanDoc_'. uniqid(). ".pdf" ;
            $filepath = $path.'/'.$filename;
            $filepath_str = asset('/files/'.$folder . '/'.$filename);
            $image_base64 = base64_decode($request['imageupload']);
            // $pdf=PDF::loadView('driver_invoice_pdf',['drivers' => $drivers,  'customers' => $customers, 'invoice_details' => $invoice_details])->setPaper('a4')->save($filepath);
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
     	
     	
     	$files = $detail->upload;
    	$names = $detail->filename;       
        
        $detail->upload = ($files=="")?$filepath_str:$files.",".$filepath_str ;
        $detail->filename = ($names=="")?$filename:$names.",".$filename;
        
        $detail->save();
        
        
        $detail = Detail::where('id',$request['detail_id'])->get();
        return response()->json(['data'=>$detail], $this->successStatus);
    }
    
    
    public function detailsdata(Request $request)
    {
       
        $detail = Detail::join('contact_lists', 'details.contact_id', 'contact_lists.id')
                                ->select('details.*', 'contact_lists.d_company_name as company')
                                ->where('details.id',$request['detail_id'])->get();
        
        $detail_origin = Detail_addr::select('*')-> where('detail_id',$request['detail_id'])->where('zip_type','origin')->orderBy('order_index')->get();
        $detail_dest = Detail_addr::select('*')-> where('detail_id',$request['detail_id'])->where('zip_type','destination')->orderBy('order_index')->get();                        
        $detaladdress = [];
        foreach ($detail_origin as $itme) {
            array_push($detaladdress, $itme);
        }

        foreach ($detail_dest as $itme) {
            array_push($detaladdress, $itme);
        }

        $LoadExpense = Load_expense::where('detail_id',$request['detail_id'])
            ->select(
                'load_expenses.id',
                'load_expenses.date',
                'load_expenses.fuel as s_Fuel',
                'load_expenses.gallons as s_Gallons',
                'load_expenses.def as s_DEF',
                'load_expenses.payroll as s_Payroll',
                'load_expenses.parking as s_Parking',
                'load_expenses.tolls as s_Tolls',
                'load_expenses.tolls_txt as toll_txt',
                'load_expenses.lumper as s_Lumper',
                'load_expenses.lumper_txt',
                'load_expenses.accomerdations as s_Hotel',
                'load_expenses.accom_txt',
                'load_expenses.other as s_Other',
                'load_expenses.total as s_Total',
                'load_expenses.total',
                'load_expenses.other_txt',
                'load_expenses.file_name',
                'load_expenses.file_path',
                'load_expenses.detail_id'
                )
            ->get();
        if(count($LoadExpense))
            return response()->json(['data'=>$detail, 'Loadexpense'=>$LoadExpense[0],'detaladdress'=>$detaladdress], $this->successStatus);
        else
            return response()->json(['data'=>$detail, 'Loadexpense'=>'false','detaladdress'=>$detaladdress], $this->successStatus);
    }
    
    
    public function detailsinvoice(Request $request)
    {
        
        $detail = Detail::where('id',$request['detail_id'])->get();
        
        $drivers = Driver::join('details', 'details.driver_id','=', 'drivers.id')
                    ->join('contact_lists', 'details.contact_id', 'contact_lists.id')
                    ->join('users','users.id','drivers.user_id')
                    ->where('details.id',$request['detail_id'])->get();
        $customers = Customer::where('id', $drivers[0]->company_id)->get();
        
        
        $data = array(
                'drivers'=>$drivers,
                'customer'=>$customers
        );
        
        return response()->json(['data'=>$data], $this->successStatus);
    }
    
    public function phonenumber(Request $request)
    {
        
        $drivers = Driver::where('company_id',$request['customer_id'])->get();
        $phonenumber = array();
        if($drivers){
            $employees = $drivers[0]->employee_id;
            $ids = explode(",", $employees);
            $users = User::whereIn('id', $ids)->get();
            foreach ($users as $user) {
                $phone_str=$user->phone;
                $str1 = str_replace(")","",$phone_str);
                $str2 = str_replace("(","",$str1);
                $str3 = str_replace(" ","",$str2);
                $phone = str_replace("-","",$str3);
                
                array_push($phonenumber, $phone);
            }
            
            return response()->json(['data'=>$phonenumber], $this->successStatus);
        }
        
        $phonenumber = array('18662728001');
        return response()->json(['data'=>$phonenumber], $this->successStatus);
    }
    public function customerfiledelete(Request $request)
    {  
        $customer = Customer::findOrFail($request['customer_id']);
        
        $files = explode(",",$customer->image_path);
        
        foreach ($files as $key => $file) {
            if(strpos($file, $request['strkey'])>0)
               unset($files[$key]); 
        }
        
        $customer->image_path = implode(',',$files);
        $customer->save();
        
        
        
        $user = Auth::user();
        
                $customers = Customer::join('users', 'users.email', '=', 'customers.email')
                    ->select('customers.id', 'customers.company', 'customers.firstname', 'customers.lastname', 'customers.phone', 'customers.image_path', 'customers.description')
                    ->where('users.email', Auth::user()->email)->get();
                    
                $drivers = Driver::join('customers', 'customers.id', '=', 'drivers.company_id')
                        ->join('users', 'users.id', '=', 'drivers.user_id')
                        ->select('drivers.id', 'drivers.company_id', 'drivers.employee_id','users.firstname', 'users.lastname', 'customers.company')
                        ->where('customers.email', Auth::user()->email)->get();
                
                $reports = Driver::join('details', 'details.driver_id', '=', 'drivers.id')
                    ->join('customers', 'customers.id', '=', 'drivers.company_id')
                    ->where('customers.email', Auth::user()->email);                
                
                $success['token'] = $user->createToken('merge')->accessToken;
                $success['userId'] = $user->id;
                $success['name'] = $user->name;
                $success['email'] = $user->email;
                $success['companyname'] = $customers[0]->company;
                $success['customersID'] = $customers[0]->id;
                $success['drivers'] = $drivers;
                $success['total_revenue'] = "$".number_format($reports->sum('details.revenue'), 2, '.', ',');
                $success['total_mile'] = number_format($reports->sum('details.mile'));
                $success['total_dho'] = number_format($reports->sum('details.dho'));

                $t_miles = $reports->sum('details.mile') + $reports->sum('details.dho');
                $success['total_rpm'] = ($t_miles)?"$".number_format(($reports->sum('details.revenue')/$t_miles),2):0;
                
                $success['role'] = $user->role;
                $success['isActive'] = $user->is_active;
                $success['firstname'] = $customers[0]->firstname;
                $success['lastname'] = $customers[0]->lastname;
                $success['phone'] = $customers[0]->phone;
                $success['description'] = $customers[0]->description;
                $success['image_path'] = $customers[0]->image_path;
                
                
                
                return response()->json(['success' => $success], $this->successStatus); 
        
        
        
    }
    
    
    public function customerupload(Request $request)
    {
        
        $customer = Customer::findOrFail($request['customer_id']);
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

     	$filename = uniqid().$request['filename'].'.jpg';
     	$filepath_str = asset('/files/'.$folder . '/'.$filename);
     	$filepath = $path.'/'.$filename;
     	$image_base64 = base64_decode($request['imageupload']);
     	
     	file_put_contents($filepath, $image_base64);
     	
     	$files = $customer->image_path;
    	
            $customer->image_path = ($files=="")?$filepath_str:$files.",".$filepath_str ;
            
        $customer->save();
        
        
        
        $user = Auth::user();
        
                $customers = Customer::join('users', 'users.email', '=', 'customers.email')
                    ->select('customers.id', 'customers.company', 'customers.firstname', 'customers.lastname', 'customers.phone', 'customers.image_path', 'customers.description')
                    ->where('users.email', Auth::user()->email)->get();
                    
                $drivers = Driver::join('customers', 'customers.id', '=', 'drivers.company_id')
                        ->join('users', 'users.id', '=', 'drivers.user_id')
                        ->select('drivers.id', 'drivers.company_id', 'drivers.employee_id','users.firstname', 'users.lastname', 'customers.company')
                        ->where('customers.email', Auth::user()->email)->get();
                
                $reports = Driver::join('details', 'details.driver_id', '=', 'drivers.id')
                    ->join('customers', 'customers.id', '=', 'drivers.company_id')
                    ->where('customers.email', Auth::user()->email);                
                
                $success['token'] = $user->createToken('merge')->accessToken;
                $success['userId'] = $user->id;
                $success['name'] = $user->name;
                $success['email'] = $user->email;
                $success['companyname'] = $customers[0]->company;
                $success['customersID'] = $customers[0]->id;
                $success['drivers'] = $drivers;
                $success['total_revenue'] = "$".number_format($reports->sum('details.revenue'), 2, '.', ',');
                $success['total_mile'] = number_format($reports->sum('details.mile'));
                $success['total_dho'] = number_format($reports->sum('details.dho'));
                
                $t_miles = $reports->sum('details.mile') + $reports->sum('details.dho');
                $success['total_rpm'] = ($t_miles)?"$".number_format(($reports->sum('details.revenue')/$t_miles),2):0;

                $success['role'] = $user->role;
                $success['isActive'] = $user->is_active;
                $success['firstname'] = $customers[0]->firstname;
                $success['lastname'] = $customers[0]->lastname;
                $success['phone'] = $customers[0]->phone;
                $success['description'] = $customers[0]->description;
                $success['image_path'] = $customers[0]->image_path;
                
                
                return response()->json(['success' => $success], $this->successStatus); 
    }
    public function customerupdate(Request $request)
    {
        
        $customer = Customer::findOrFail($request['customer_id']);
     	$customer->company = $request['company'];
     	$customer->firstname = $request['firstname'];
     	$customer->lastname = $request['lastname'];
     	$customer->description = $request['desc'];
     	$customer->phone = $request['phone'];
     	$customer->street = $request['street'];
     	$customer->city = $request['city'];
     	$customer->state = $request['state'];
     	$customer->zipcode = $request['zipcode'];
        $customer->save();
        
        
        
        $users = User::findOrFail($request['user_id']);
     	$users->firstname = $request['firstname'];
     	$users->lastname = $request['lastname'];
     	$users->phone = $request['phone'];
     	
        $users->save();    
        
        
        $user = Auth::user();
        
                $customers = Customer::join('users', 'users.email', '=', 'customers.email')
                    ->select('customers.id', 'customers.company', 'customers.street', 'customers.city', 'customers.state', 'customers.zipcode', 'customers.firstname', 'customers.lastname', 'customers.phone', 'customers.image_path', 'customers.description')
                    ->where('users.email', Auth::user()->email)->get();
                    
                $drivers = Driver::join('customers', 'customers.id', '=', 'drivers.company_id')
                        ->join('users', 'users.id', '=', 'drivers.user_id')
                        ->select('drivers.id', 'drivers.company_id', 'drivers.employee_id','users.firstname', 'users.lastname', 'customers.company')
                        ->where('customers.email', Auth::user()->email)->get();
                
                $reports = Driver::join('details', 'details.driver_id', '=', 'drivers.id')
                    ->join('customers', 'customers.id', '=', 'drivers.company_id')
                    ->where('customers.email', Auth::user()->email);                
                
                $success['token'] = $user->createToken('merge')->accessToken;
                $success['userId'] = $user->id;
                $success['name'] = $user->name;
                $success['email'] = $user->email;
                $success['companyname'] = $customers[0]->company;
                $success['customersID'] = $customers[0]->id;
                $success['drivers'] = $drivers;
                $success['total_revenue'] = "$".number_format($reports->sum('details.revenue'), 2, '.', ',');
                $success['total_mile'] = number_format($reports->sum('details.mile'));
                $success['total_dho'] = number_format($reports->sum('details.dho'));

                $t_miles = $reports->sum('details.mile') + $reports->sum('details.dho');
                $success['total_rpm'] = ($t_miles)?"$".number_format(($reports->sum('details.revenue')/$t_miles),2):0;
                $success['role'] = $user->role;
                $success['isActive'] = $user->is_active;
                $success['firstname'] = $customers[0]->firstname;
                $success['lastname'] = $customers[0]->lastname;
                $success['phone'] = $customers[0]->phone;
                $success['street'] = $customers[0]->street;
                $success['city'] = $customers[0]->city;
                $success['state'] = $customers[0]->state;
                $success['zipcode'] = $customers[0]->zipcode;
                
                
                $success['description'] = $customers[0]->description;
                $success['image_path'] = $customers[0]->image_path;
                
                
                return response()->json(['success' => $success], $this->successStatus); 
    }
    
    public function changepassword(Request $request)
    {
        $user = Auth::user();
        $hashedPassword = $user->password;
        
        if (Hash::check($request['currentPassword'], $hashedPassword)) {
                $users = User::findOrFail($user->id);
                $users->password=bcrypt($request['newPassword']);
                $users->save();
                return response()->json(['data'=>'successful'], $this->successStatus);
        }else{
            return response()->json(['data'=>'ErrorTrue'], $this->successStatus);
        }
    }

    public function memberupgrade(Request $request)
    {

        $stripe_key = env("STRIPE_SECRET_KEY");
        Stripe::setApiKey($stripe_key);



        try {
            
            $customer= Customer::find($request["customersID"]);
            $currentMemberType = $customer->member_type;
            $customer->member_type = $request['memberType'];
            switch ($request['memberType']) {
                case 1:
                    break;
                case 2:
                    if ($request['newCardOption'] == true) {
                        // dd($request['stripe_token']);   
                        $customer->card_token = $request['cardTokenID'];  
                        $stripe_user = User::find(Auth::user()->id);
                        // dd($stripe_user);
                        if (!empty($stripe_user->stripe_id)) {
                            $stripe_user->updateCard($request['cardTokenID']);
                            $stripe_user->save();   
                        } else {
                            $stripe_user->createAsStripeCustomer($request['cardTokenID'], [
                                'email' => Auth::user()->email,
                                "description" => "Mergetransit"
                            ]);
                            $stripe_user->save();
                                            
                            $stripe_user->charge(9900, [
                                'description' => 'Merget Transit - Organization',
                            ]);
                        }              
                    } 
                    break;
                case 3:

                    $stripe_user = User::find(Auth::user()->id);
                    if ($request['newCardOption'] == true) {
                        $customer->card_token = $request['cardTokenID'];  
                    
                        if (!empty($stripe_user->stripe_id)) {
                                $stripe_user->updateCard($request['cardTokenID']);
                                $stripe_user->save();   
                        
                            
                        } else {
                                $stripe_user->createAsStripeCustomer($request['cardTokenID'], [
                                    'email' => Auth::user()->email,
                                    "description" => "Mergetransit"
                                ]);
                                $stripe_user->save(); 
                            
                        }   
                                
                    } 
                    if($currentMemberType != 3) {
                            $stripe_user->charge(12500, [
                                'description' => 'Merget Transit - Optimization',
                            ]);                     
                    }
                    break;
                default: 
                    break;

            }
        
        $customer->save();
        $result['card_brand'] = Auth::user()->card_brand;
        $result['card_last_four'] = Auth::user()->card_last_four;
        return response()->json(['data'=>'successful', 'card_info'=>$result], $this->successStatus);
    } catch(\Exception $e) {
        $success = false;
        DB::rollback();     
        
        $result['status'] = false;
        $result['message'] = $e->getMessage();     
        return response()->json(['data'=>'failed','message'=>$e->getMessage()], $this->successStatus);       
    }

        

        // $customer = Customer::findOrFail($request["customersID"]);
        // $customer->member_type= $request['memberType'];
        // $customer->card_token = $request['cardTokenID'];
        // $customer->save();
        // return response()->json(['data'=>'successful'], $this->successStatus);


    }
    
    
    
    public function delete_file(Request $request){
        $id = $request['detail_id'];
        $no = $request['no'];
        $detail = Detail::findOrFail($id);
        $files_arr = explode(",",$detail->upload);
        $names_arr = explode(",",$detail->filename);
        
        unset($files_arr[$no]); $refile_arr = array_values($files_arr); 
        unset($names_arr[$no]); $rename_arr = array_values($names_arr);         
        $detail->upload = implode(',',$refile_arr);
        $detail->filename = implode(',',$rename_arr);
        
        $save_status = $detail->save();
        if($save_status){
            
            $detail = Detail::where('id',$id)->get();
            return response()->json(['data'=>$detail], $this->successStatus);
        }
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
