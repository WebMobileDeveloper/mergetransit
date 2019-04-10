<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data=[];
        // $this->create_distance_table();
        
        return view('index', $data);
    }


    public function gotoTarget($url)
    {
        if (view()->exists($url)) {
            $data=[];
            if (in_array($url, ['aboutus','pricing','download','faq','contactus'])) {
                $data['white_header'] = true;
            }
            if (in_array($url, ['download'])) {
                $data['hide_app_section'] = true;
            }
            if ($url=='faq') {
                // $faqs=DB::table('faq')->get()->toArray();   
                // for($i=0; $i<count($faqs); $i++){
                //     $faqs[$i]->groups=DB::table('faq_ans_group')->where('faq_id', $faqs[$i]->id)->get()->toArray();  
                //     for($j=0; $j<count($faqs[$i]->groups); $j++){
                //         $faqs[$i]->groups[$j]->answers=DB::table('faq_answers')->where('group_id', $faqs[$i]->groups[$j]->id)->get()->toArray();      
                //     }                  
                // }               

                // $data['faqs']=$faqs;
                // dd($data['faqs']);
            }
            return view($url, $data);
        } else {
            return view('404page')->render();
        }
    }
    // public function create_distance_table()
    // {
    //     $locations =  DB::select('select * from locations');
    //     $routes=[];
    //     foreach ($locations as $location) {
    //         $routes[$location->route]=$location;
    //     }

    //     $fields= DB::getSchemaBuilder()->getColumnListing('dat_input');
    //     for ($i=2; $i<count($fields); $i++) {
    //         $arr = str_split($fields[$i], 3);
    //         $p1=$routes[$arr[0]];
    //         $p2 = $routes[$arr[1]];
    //         $miles = $this->get_distance_mile($p1->lat, $p1->long, $p2->lat, $p2->long);
    //         DB::table('distance')->insert([
    //             "route"         => $fields[$i],
    //             "from"          =>$arr[0],
    //             "to"            =>$arr[1],
    //             "from_airport"  =>$p1->city,
    //             "to_airport"    =>$p2->city,
    //             "from_lat"      =>$p1->lat,
    //             "from_long"     =>$p1->long,
    //             "to_lat"        =>$p2->lat,
    //             "to_long"       =>$p2->long,
    //             "miles"         =>$miles,
    //             "kms"           =>$miles * 1.609344,
    //             "nautical_miles"=>$miles * 0.8684
    //             ]);
    //     }
    // }
    // public function get_distance_mile($lat1, $lon1, $lat2, $lon2)
    // {
    //     if (($lat1 == $lat2) && ($lon1 == $lon2)) {
    //         return 0;
    //     } else {
    //         $theta = $lon1 - $lon2;
    //         $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    //         $dist = acos($dist);
    //         $dist = rad2deg($dist);
    //         $miles = $dist * 60 * 1.1515;
    //         return $miles;
    //     }
    // }
}
