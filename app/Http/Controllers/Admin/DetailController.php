<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Driver;
use App\Customer;
use App\User;
use App\Detail;
use App\Detail_addr;
use App\Contact_list;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use File;
use Illuminate\Support\Facades\URL;

class DetailController extends Controller
{
    public $apiKey = "AIzaSyAr1HliRAne44OuG55a6FOOornx_dHgBjA";
    public $zipCount = 0;
    public $distCount=0;
    public $zip_array =[];
    public $coordArr=[];
    public $lengthArr=[];
    public $total_distance =[];

    public function __construct()
    {
       
    }
    public function index(Request $request)
    {
        if(Auth::user() == NULL) {
            return redirect('admin');
        }
        $login_user_id = Auth::user()->id;
        
        $s = $request->s;
        $details = Detail::join('drivers', 'details.driver_id', '=', 'drivers.id');
                       
        $details=$details->select('details.*', 'drivers.user_id');
       
        $details=$details->whereRaw('FIND_IN_SET(?,drivers.employee_id)', [$login_user_id]);   
        if(isset($s))$details -> search($s);   
        
        $details=$details->orderBy('details.created_at','desc')->paginate(10);         
       
     
        return view('admin.details.index', compact('details','s'));
    }

   
    public function create()
    {
        $drivers = Driver::join('users', 'users.id', '=', 'drivers.user_id')
                            ->join('customers', 'customers.id', '=', 'drivers.company_id')
                            ->select('drivers.id', 'drivers.company_id','drivers.employee_id', 'users.firstname', 'users.lastname', 'customers.company');
        // $drivers = $drivers->where('drivers.employee_id', Auth::user()->id);
        $drivers = $drivers->get();
        $contacts = Contact_list::select('*')->get();
        return view('admin.details.detailAdd', compact('drivers','contacts'));
    }

    public function store(Request $request)
    {
        try {
           
            if($request['contact_id'] != 0)  {
                $contactlist = Contact_list::findOrFail($request['contact_id']);
                $contactlist->address1= $request['address1'];
                $contactlist->address2= $request['address2'];
                $contactlist->city= $request['city'];
                $contactlist->state= $request['state'];
                $contactlist->zipcode= $request['zipcode'];
                $contactlist->save();
            }
            //var_dump("Dddddd",$request['shipper'] );exit;
           
            $detail = new Detail();

            $detail->driver_id= $request['driver_id'];
            $detail->contact_id= $request['contact_id'];      
            $detail->contact= $request['contact'];
            $detail->po= $request['po'];
            $detail->load_num   = $request['load_num'];
            $detail->put_date   = $request['put_date'];
            $detail->del_date   = $request['del_date'];
            $detail->shipper    = $request['shipper'][0];
            $detail->origin     = $request['origin_city'][0] . "," . $request['origin_province'][0];
            $detail->origin_zip =  $request['origin_zipcode'][0];

            $last_dest_num = count($request['consignee']) - 1;

            $detail->consignee= $request['consignee'][$last_dest_num];
            $detail->destination= $request['destination_city'][$last_dest_num] . "," . $request['destination_province'][$last_dest_num];
            $detail->destination_zip= $request['destination_zipcode'][$last_dest_num];
            $detail->weight= $request['weight'];

            //extra amount for revenue
            $accessorials = 0;
            $detention = 0;
            for($i=0; $i<count($request['extra_reason']); $i++) {
                $accessorials = ($request['extra_reason'][$i] == 'accessorials') ? $accessorials + $request['extra_amount'][$i] :$accessorials;
                $detention = ($request['extra_reason'][$i] == 'detention') ? $detention + $request['extra_amount'][$i] :$detention;
               
            }
            $extra_arr = array(
                'accessorials' => $accessorials,
                'detention'=>$detention
            );
            $detail->amount= $request['amount'];
            $detail->revenue= floatval($request['amount']) +  $accessorials + $detention;
            $detail->extra_revenue= json_encode($extra_arr);  

            $detail->last_dest_zip= $request['destination_zipcode'][$last_dest_num];
            $detail->last_dest_address= $request['destination_city'][$last_dest_num] . "," . $request['destination_province'][$last_dest_num];    
            $detail->last_dest_lat= $request['destination_lat'][$last_dest_num] ;
            $detail->last_dest_lng= $request['destination_lng'][$last_dest_num] ;

            $detail->mile= $request['mile'];
            $detail->dho= $request['dho'];
            $detail->rpm= $request['rpm'];
            $detail->dh_rpm= $request['dh_rpm'];

            $file_arr = "";
            $filename_arr="";
            if($request->file('upload_file')!=null){
                $n = 0;
                foreach($request->file('upload_file') as $uploadfile){
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

                    $uniqueFileName = uniqid(). "." . $uploadfile->getClientOriginalExtension();       
                    $upload_status = $uploadfile->move($path , $uniqueFileName);
                    $file_arr = ($n==0)?asset('/files/'.$folder . '/' .$uniqueFileName):$file_arr.",".asset('/files/'.$folder . '/'.$uniqueFileName);
                    
                    $filename_arr = ($n==0)?$uniqueFileName:$filename_arr.",".$uniqueFileName;
                    
                    $n++;
                }
            }
            $detail->upload = $file_arr ;
            $detail->filename = $filename_arr;
            $detail->save();
           
            if ($request['detail_id'] == 0) {
                
                for ($i = 0; $i < count($request['shipper']); $i++) {
                    $detail_addr = new Detail_addr();
                    $detail_addr->detail_id = $detail->id;
                    $detail_addr->name = $request['shipper'][$i];
                    $detail_addr->zip_type = 'origin';
                    $detail_addr->lat = $request['origin_lat'][$i];
                    $detail_addr->lng = $request['origin_lng'][$i];
                    $detail_addr->street = $request['origin_street'][$i];
                    $detail_addr->zip = $request['origin_zipcode'][$i];
                    $detail_addr->address = $request['origin_city'][$i] . "," . $request['origin_province'][$i];
                    $detail_addr->order_index = $i;
                    $detail_addr->save();
                }

                for ($i = 0; $i < count($request['consignee']); $i++) {
                    $detail_addr = new Detail_addr();
                    $detail_addr->detail_id = $detail->id;
                    $detail_addr->name = $request['consignee'][$i];
                    $detail_addr->zip_type = 'destination';
                    $detail_addr->lat = $request['destination_lat'][$i];
                    $detail_addr->lng = $request['destination_lng'][$i];
                    $detail_addr->street = $request['destination_street'][$i];
                    $detail_addr->zip = $request['destination_zipcode'][$i];
                    $detail_addr->address = $request['destination_city'][$i] . "," . $request['destination_province'][$i];
                    $detail_addr->order_index = $i;
                    $detail_addr->save();
                }
            }
           
            $success = true;
            
        } catch (\Exception $e) {
            dd($e);
            $success = false;
        }

        if ($success) {
            return redirect('admin/details');
        } else {
           
            return redirect('admin/details');
        }

    }

    public function show($id)
    {
        return redirect('admin/details');
    }

    public function edit($id)
    {
        $drivers = Driver::join('users', 'users.id', '=', 'drivers.user_id')
        ->join('customers', 'customers.id', '=', 'drivers.company_id')
        ->select('drivers.id','drivers.employee_id', 'drivers.company_id', 'users.firstname', 'users.lastname', 'customers.company');
        // $drivers = $drivers->where('drivers.employee_id', Auth::user()->id)->get();
        $drivers = $drivers->get();

        $_detail = Detail::select('details.*')->
                        where('details.id',$id)->get();
        $detail = $_detail[0];
        $contacts = Contact_list::select('*')->get();
        $detail_origin = Detail_addr::select('*')-> where('detail_id',$id)->where('zip_type','origin')->orderBy('order_index')->get();
        $detail_dest = Detail_addr::select('*')-> where('detail_id',$id)->where('zip_type','destination')->orderBy('order_index')->get();
       
        return view('admin.details.detailEdit', compact('drivers','detail','contacts','detail_origin','detail_dest'));
    }

    public function update(Request $request, $id)
    {
       
        try {

            if($request['contact_id'] != 0)  {
                $contactlist = Contact_list::findOrFail($request['contact_id']);
                $contactlist->address1= $request['address1'];
                $contactlist->address2= $request['address2'];
                $contactlist->city= $request['city'];
                $contactlist->state= $request['state'];
                $contactlist->zipcode= $request['zipcode'];
                $contactlist->save();
            }

            $detail = Detail::findOrFail($id);
            $detail->driver_id= $request['driver_id'];
            $detail->contact_id= $request['contact_id'];    
            $detail->contact= $request['contact'];
            $detail->po= $request['po'];
            $detail->load_num = $request['load_num'];
            $detail->put_date= $request['put_date'];
            $detail->del_date= $request['del_date'];
           
            $detail->shipper= $request['shipper'][0];
            $detail->origin= $request['origin_city'][0] . "," . $request['origin_province'][0];
            $detail->origin_zip=  $request['origin_zipcode'][0];

            $last_dest_num = count($request['consignee']) - 1;

            $detail->consignee= $request['consignee'][$last_dest_num];
            $detail->destination= $request['destination_city'][$last_dest_num] . "," . $request['destination_province'][$last_dest_num];
            $detail->destination_zip= $request['destination_zipcode'][$last_dest_num];

            $detail->weight= $request['weight'];
             
            
            //extra amount for revenue
            $accessorials = 0;
            $detention = 0;
            for($i=0; $i<count($request['extra_reason']); $i++) {
                $accessorials = ($request['extra_reason'][$i] == 'accessorials') ? $accessorials + $request['extra_amount'][$i] :$accessorials;
                $detention = ($request['extra_reason'][$i] == 'detention') ? $detention + $request['extra_amount'][$i] :$detention;
               
            }
            $extra_arr = array(
                'accessorials' => $accessorials,
                'detention'=>$detention
            );
            $detail->amount= $request['amount'];
            $detail->revenue= floatval($request['amount']) +  $accessorials + $detention;
            $detail->extra_revenue= json_encode($extra_arr);  
            
            $last_dest_num = count($request['consignee']) - 1;
            $detail->last_dest_zip= $request['destination_zipcode'][$last_dest_num];
            $detail->last_dest_address= $request['destination_city'][$last_dest_num] . "," . $request['destination_province'][$last_dest_num];    
            $detail->last_dest_lat= $request['destination_lat'][$last_dest_num] ;
            $detail->last_dest_lng= $request['destination_lng'][$last_dest_num] ;
            
            $detail->mile= $request['mile'];
            $detail->dho= $request['dho'];
            $detail->rpm= $request['rpm'];
            $detail->dh_rpm= $request['dh_rpm'];
            if($request->file('upload_file')!=null){
                $n = 0;
                $files = $detail->upload;
                $names = $detail->filename;
            // 	var_dump($_FILES['upload_file']);exit;
                foreach($_FILES['upload_file']['tmp_name'] as $uploadfile){
                    $ext_arr = explode(".",$_FILES['upload_file']['name'][$n] );
                    
                    // var_dump($ext_arr[1]);exit;
                    $uniqueFileName = uniqid(). "-". $n ."." . $ext_arr[1];       
                // $upload_status = $uploadfile->move(public_path('files') , $uniqueFileName);
                    
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


                    $upload_status = move_uploaded_file($uploadfile, $path."/".$uniqueFileName);
                    $file_arr = ($n==0)?asset('/files/'.$folder.'/'.$uniqueFileName):$file_arr.",".asset('/files/'.$folder.'/'.$uniqueFileName);
                    
                    $filename_arr = ($n==0)?$uniqueFileName:$filename_arr.",".$uniqueFileName;
                    
                    $n++;
                }
            
                $detail->upload = ($files=="")?$file_arr:$files.",".$file_arr ;
                $detail->filename = ($names=="")?$filename_arr:$names.",".$filename_arr;
            }
            $detail->save();    
            
            
                
            for ($i = 0; $i < count($request['shipper']); $i++) {
                if ($request['origin_detail_addr_id'][$i] == 0) {
                    $detail_addr = new Detail_addr();
                } else {
                    $detail_addr = Detail_addr::find($request['origin_detail_addr_id'][$i]);
                }
               
                $detail_addr->detail_id = $detail->id;
                $detail_addr->name = $request['shipper'][$i];
                $detail_addr->zip_type = 'origin';
                if (!empty($request['origin_lat'][$i])) {
                    $detail_addr->lat = $request['origin_lat'][$i];
                }
                if (!empty($request['origin_lng'][$i])) {
                    $detail_addr->lng = $request['origin_lng'][$i];
                }
                $detail_addr->street = $request['origin_street'][$i];
                $detail_addr->zip = $request['origin_zipcode'][$i];
                $detail_addr->address = $request['origin_city'][$i] . "," . $request['origin_province'][$i];
                $detail_addr->order_index = $i;
                $detail_addr->save();
            }

            for ($i = 0; $i < count($request['consignee']); $i++) {
                
                if ($request['dest_detail_addr_id'][$i] == 0) {
                    $detail_addr = new Detail_addr();
                } else {
                    $detail_addr = Detail_addr::find($request['dest_detail_addr_id'][$i]);
                }
                $detail_addr->detail_id = $detail->id;
                $detail_addr->name = $request['consignee'][$i];
                $detail_addr->zip_type = 'destination';
                
                if (!empty($request['destination_lat'][$i])) {
                    $detail_addr->lat = $request['destination_lat'][$i];
                }
                if (!empty($request['destination_lng'][$i])) {
                    $detail_addr->lng = $request['destination_lng'][$i];
                }
                $detail_addr->street = $request['destination_street'][$i];
                $detail_addr->zip = $request['destination_zipcode'][$i];
                $detail_addr->address = $request['destination_city'][$i] . "," . $request['destination_province'][$i];
                $detail_addr->order_index = $i;
                $detail_addr->save();
            }
           

            $success = true;
                
        } catch (\Exception $e) {
            $success = false;
        }

        if ($success) {
            return redirect('admin/details');
        } else {
            return redirect('admin/details');
        }
    }

    
    
    private function filter($components, $type)
     {
        foreach($components as $addrComp){
            if($addrComp->types[0] == $type){
                //Return the zipcode
                return $addrComp->long_name;
            } 

        }
     }

    /**
     * 
     * Get distance from coordinator
     */

    function GetDrivingDistance($lat1, $long1, $lat2,$long2)
    {
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$lat1.",".$long1."&destinations=".$lat2.",".$long2."&mode=driving&language=EN&key=AIzaSyAr1HliRAne44OuG55a6FOOornx_dHgBjA";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        $response_a = json_decode($response, true);
        $dist = $response_a['rows'][0]['elements'][0]['distance']['text'];
        $time = $response_a['rows'][0]['elements'][0]['duration']['text'];

        $value = (double)filter_var($dist, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $distance = $value * 0.621371;
        if(!isset($distance) || !$distance){
            $distance = 0;
        }   
        // $this->total_distance+=$distance;
        array_push($this->total_distance, $distance);
        $this->distCount++;
        
        if($this->distCount == count($this->coordArr)-1){
            $this->sendResponse();
        }
    }

    

    public function delete_detail_address(Request $request) {
        try{
            $detail_addr_id = $request['detail_addr_id'];
            $detail_addr = Detail_addr::find($detail_addr_id);
            $detail_addr->delete();
            $success = true;

        } catch (\Exception $e) {
            $success = false;
        }

        if ($success) {
            $result['status'] = 'success';
        } else {
            $result['status'] = 'failed';
        }
        die(json_encode($result));
    }
    /**
     * 
     * 
     */
    public function tofloat($num) {
        $dotPos = strrpos($num, '.');
        $commaPos = strrpos($num, ',');
        $sep = (($dotPos > $commaPos) && $dotPos) ? $dotPos :
            ((($commaPos > $dotPos) && $commaPos) ? $commaPos : false);
    
        if (!$sep) {
            return floatval(preg_replace("/[^0-9]/", "", $num));
        }

        return floatval(
            preg_replace("/[^0-9]/", "", substr($num, 0, $sep)) . '.' .
            preg_replace("/[^0-9]/", "", substr($num, $sep+1, strlen($num)))
        );
    }

    /**
     * 
     */

    public function sendResponse(){
        
        if (count($this->total_distance)==0) {
            $result['status']='failed';
        } else {
            $result['status']='success';
            $result['dho'] = $this->total_distance[0];
            $dist = 0;
            for($i=1; $i<count($this->total_distance);$i++) {
                $dist+=$this->total_distance[$i];
            }
            $result['distance'] = $dist;
            $result['zip_arr'] =$this->zip_array;
            $result['total_dist_arr'] = $this->total_distance;
        }

        die(json_encode($result));
    } 
    public function get_miles(Request $request){

        //get DHO of this driver
        $driver_id = $request['driver_id'];
        $detail_id = $request['detail_id'];
       
        //get last destination address
        $details = Detail::where("driver_id", $driver_id)
                    ->orderBy("del_date","DESC")
                    ->get();
       

        if($detail_id == 0) {
            if (count($details) > 0) {
                $last_detail = array(                  
                    'last_lat' => $details[0]->last_dest_lat,
                    'last_lng' => $details[0]->last_dest_lng,
                    'last_zip' => $details[0]->last_dest_zip
                );
            }else {
                $last_detail = array(                  
                    'last_lat' => $request['origin_lat'][0],
                    'last_lng' => $request['origin_lng'][0],
                    'last_zip' => $request['origin_zip'][0]
                );
            }
            
        }
        else {
            $flag = false;           
            foreach ($details as $detail ) {
                if($flag ) {
                    $last_detail  = array(                       
                        'last_lat' => $detail->last_dest_lat,
                        'last_lng' => $detail->last_dest_lng,
                        'last_zip' => $details[0]->last_dest_zip
                    );
                    $flag = false;
                }
                if ($detail->id == $detail_id) {
                    $flag = true;
                }               
            }

        }
       
       
        $origin_coordArr = array();
        for ($i=0; $i<count($request['origin_zip']); $i++) {
            $origin_coordArr[$i] = array(
                'lat'=> $request['origin_lat'][$i],
                'long'=>$request['origin_lng'][$i],
                'zip'=>$request['origin_zip'][$i]
            );
        }
        $dest_coordArr = array();
        for ($i=0; $i<count($request['destination_zip']); $i++) {
            $dest_coordArr[$i] = array(
                'lat'=> $request['destination_lat'][$i],
                'long'=>$request['destination_lng'][$i],
                'zip'=>$request['destination_zip'][$i]
            );
        }

        $last_coordArr[0] = array (
            'lat'=> (!empty($last_detail['last_lat']))? $last_detail['last_lat']:$origin_coordArr[0]['lat'],
            'long'=>(!empty($last_detail['last_lng']))?$last_detail['last_lng']:$origin_coordArr[0]['long'],
            'zip'=>(!empty($last_detail['last_zip']))?$last_detail['last_zip']:$origin_coordArr[0]['zip']
        );
       
        
        $this->coordArr = array_merge($last_coordArr,$origin_coordArr,$dest_coordArr);

        // dd($this->coordArr);
        $this->total_distance = [];
        $this->distCount = 0;

        for ($i = 0; $i < count($this->coordArr)-1  ; $i++) { 
            $origin=$this->coordArr[$i];    
            $dest=$this->coordArr[$i+1];
            if ( !$origin || !$dest )
            {
                $distance = 0;
            } else {
                $this->GetDrivingDistance($origin['lat'], $origin['long'], $dest['lat'], $dest['long']);
            }           
        }    
      
    }

    public function destroy($id, $type)
    {
        
        $detail = Detail::find($id);
        $detail->delete();
        if($type=='detail'){
            return redirect('admin/details');
        } else {
            return redirect('admin/reports');
        }
        
    }
    
    public function delete_file($id, $no){
    	 $detail = Detail::findOrFail($id);
    	 $files_arr = explode(",",$detail->upload);
         $names_arr = explode(",",$detail->filename);
         
         unset($files_arr[$no]); $refile_arr = array_values($files_arr); 
         unset($names_arr[$no]); $rename_arr = array_values($names_arr);         
         $detail->upload = implode(',',$refile_arr);
         $detail->filename = implode(',',$rename_arr);
         
         $save_status = $detail->save();
         if($save_status){
         	
         	die("ok");
         }
    }
}
