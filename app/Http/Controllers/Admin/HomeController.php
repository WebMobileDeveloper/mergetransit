<?php

namespace App\Http\Controllers\Admin;

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

class HomeController extends Controller
{
    //
    public function __constructor()
    {
        $this->middleware("auth");
    }

    public function index()
    {

        // whereRaw('FIND_IN_SET(?,drivers.employee_id)', [$user_id]);

        if (Auth::user()->role == 3) {
            $emp_page = true;
            $emp = Auth::user();
            $drivers = Driver::select('id')->whereRaw("find_in_set($emp->id,employee_id)")->get();
            if (count($drivers) != 0) {
                foreach ($drivers as $driver) {
                    $driver_arr[$emp->id][] = $driver->id;
                }
                $total_detail = Detail::whereIn('driver_id', $driver_arr[$emp->id])->get()->count();
                $total_revenue = Detail::whereIn('driver_id', $driver_arr[$emp->id])->sum('revenue');
                $total_mile = Detail::whereIn('driver_id', $driver_arr[$emp->id])->sum('mile');
                $total_dhmile = Detail::whereIn('driver_id', $driver_arr[$emp->id])->sum('dho');
                $total_add_mile  = $total_mile + $total_dhmile;
            }
            $total_detail_broker =  [];
        } else {
            $emp_page = false;
            //total dispatch input number
            $total_detail = Detail::get()->count();
            //total amount revenue
            $total_revenue = Detail::sum('revenue');

            //billed amount
            $total_mile = Detail::sum('mile');
            $total_dhmile = Detail::sum('dho');
            $total_add_mile  = $total_mile + $total_dhmile;

            //weekly and monthly data
            $employees = User::where('role', 3)->get();

            foreach ($employees as $emp) {

                if ($emp->id) {
                    $drivers = Driver::select('id')->whereRaw("find_in_set($emp->id,employee_id)")->get();
                    if (count($drivers) != 0) {
                        foreach ($drivers as $driver) {
                            $driver_arr[$emp->id][] = $driver->id;
                        }
                        $total_details = Detail::whereIn('driver_id', $driver_arr[$emp->id])->get()->count();
                        $total_revenues = Detail::whereIn('driver_id', $driver_arr[$emp->id])->sum('revenue');
                        $total_miles = Detail::whereIn('driver_id', $driver_arr[$emp->id])->sum('mile');
                        $total_dhmiles = Detail::whereIn('driver_id', $driver_arr[$emp->id])->sum('dho');
                        $total_add_miles  = $total_miles + $total_dhmiles;
                        $driver_count = count($driver_arr[$emp->id]);
                        $employee = User::find($emp->id);
                        $detail_array[$emp->id] = array(
                            'employee_name' => $employee->firstname . " " . $employee->lastname,
                            'driver_array' => $driver_arr[$emp->id],
                            'detail_num' => ($total_details != null) ? number_format($total_details, 2, '.', ',') : 0,
                            'total_revenue' => ($total_revenues != null) ? number_format($total_revenues, 2, '.', ',') : 0,
                            'avg_dhrpm' => (($total_add_miles) != 0) ? number_format($total_revenues / ($total_add_miles), 2, '.', ',') : 0,
                            'total_miles' => ($total_add_miles != null) ? number_format(($total_add_miles), 2, '.', ',') : 0,
                            'avg_revenue' => ($total_details != 0) ? number_format($total_revenues / $total_details, 2, '.', ',') : 0
                        );
                    }
                }
            }
            $total_detail_broker =  $detail_array;
        }
        $infor_array = array(
            'detail_num' => $total_detail,
            'total_revenue' => number_format($total_revenue, 2, '.', ','),
            'avg_dhrpm' => number_format(($total_revenue / $total_add_mile), 2, '.', ','),
            'total_mile' => number_format($total_add_mile, 2, '.', ','),
        );
        return view("admin.home", compact('infor_array', 'total_detail_broker'));
    }


    public function total_view(Request $request)
    {

        $fromdate = $request['fromdate'];
        $todate = $request['todate'];
        if (Auth::user()->role == 3) {
            $emp = Auth::user();
            $drivers = Driver::select('id')->whereRaw("find_in_set($emp->id,employee_id)")->get();
            if (count($drivers) != 0) {
                foreach ($drivers as $driver) {
                    $driver_arr[$emp->id][] = $driver->id;
                }
                if ($fromdate != "") {
                    $total_detail = Detail::whereBetween('put_date', [$fromdate, $todate . " 23:59:59"])->whereIn('driver_id', $driver_arr[$emp->id])->get()->count();
                    $total_revenue = Detail::whereBetween('put_date', [$fromdate, $todate . " 23:59:59"])->whereIn('driver_id', $driver_arr[$emp->id])->sum('revenue');
                    $total_mile = Detail::whereBetween('put_date', [$fromdate, $todate . " 23:59:59"])->whereIn('driver_id', $driver_arr[$emp->id])->sum('mile');
                    $total_dhmile = Detail::whereBetween('put_date', [$fromdate, $todate . " 23:59:59"])->whereIn('driver_id', $driver_arr[$emp->id])->sum('dho');
                    // $driver_count=count( $driver_arr[$emp->id]);
                } else {
                    $total_detail = Detail::whereIn('driver_id', $driver_arr[$emp->id])->get()->count();
                    $total_revenue = Detail::whereIn('driver_id', $driver_arr[$emp->id])->sum('revenue');
                    $total_mile = Detail::whereIn('driver_id', $driver_arr[$emp->id])->sum('mile');
                    $total_dhmile = Detail::whereIn('driver_id', $driver_arr[$emp->id])->sum('dho');
                    // $driver_count=count( $driver_arr[$emp->id]);
                }
            }
        } else {
            if ($fromdate != "") {
                $total_detail = Detail::whereBetween('put_date', [$fromdate, $todate . " 23:59:59"])->get()->count();
                $total_revenue = Detail::whereBetween('put_date', [$fromdate, $todate . " 23:59:59"])->sum('revenue');
                $total_mile = Detail::whereBetween('put_date', [$fromdate, $todate . " 23:59:59"])->sum('mile');
                $total_dhmile = Detail::whereBetween('put_date', [$fromdate, $todate . " 23:59:59"])->sum('dho');
            } else {
                $total_detail = Detail::get()->count();
                $total_revenue = Detail::sum('revenue');
                $total_mile = Detail::sum('mile');
                $total_dhmile = Detail::sum('dho');
            }
        }
        $total_add_mile = $total_mile + $total_dhmile;
        $infor_array = array(
            'detail_num' => $total_detail,
            'total_revenue' => number_format($total_revenue, 2, '.', ','),
            'avg_dhrpm' => (($total_add_mile) != 0) ? number_format($total_revenue / ($total_add_mile), 2, '.', ',') : 0,
            'total_mile' => number_format(($total_add_mile), 2, '.', ',')
        );
        $result['status'] = "ok";
        $result['detail_info'] = $infor_array;
        return response()->json($result, 200);
    }

    public function yearly_data_get()
    {

        $month_arr = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
        $color_arr = array("#f39c12", "#03f3f2");
        $year_data['labels'] = $month_arr;

        $currYear = date("Y");

        for ($i = 0; $i <= 1; $i++) {
            $year_data['datasets'][] = array();
            $temp = &$year_data['datasets'][$i];
            $temp['label'] = ($currYear - $i) . '';
            $temp['backgroundColor'] = $color_arr[$i];
            for ($month = 1; $month <= 12; $month++) {
                $fromdate = ($currYear - $i) . "-" . ($month > 9 ? '' : '0') . $month . '-01';
                $todate = ($currYear - $i) . "-" . ($month > 9 ? '' : '0') . $month . '-31';
                if (Auth::user()->role == 3) {
                    $emp = Auth::user();
                    $drivers = Driver::select('id')->whereRaw("find_in_set($emp->id,employee_id)")->get();
                    if (count($drivers) != 0) {
                        foreach ($drivers as $driver) {
                            $driver_arr[$emp->id][] = $driver->id;
                        }
                        $data = Detail::whereBetween('put_date', [$fromdate, $todate])->whereIn('driver_id', $driver_arr[$emp->id])->get()->count();

                        $total_revenue = Detail::whereBetween('put_date', [$fromdate, $todate . " 23:59:59"])->whereIn('driver_id', $driver_arr[$emp->id])->sum('revenue');
                        $total_miles = Detail::whereBetween('put_date', [$fromdate, $todate . " 23:59:59"])->whereIn('driver_id', $driver_arr[$emp->id])->sum('mile');
                        $total_dhmiles = Detail::whereBetween('put_date', [$fromdate, $todate . " 23:59:59"])->whereIn('driver_id', $driver_arr[$emp->id])->sum('dho');
                        $driver_count = Detail::whereBetween('put_date', [$fromdate, $todate . " 23:59:59"])->whereIn('driver_id', $driver_arr[$emp->id])->groupBy('driver_id')->count();
                    }
                } else {
                    $data = Detail::whereBetween('put_date', [$fromdate, $todate])->get()->count();

                    $total_revenue = Detail::whereBetween('put_date', [$fromdate, $todate . " 23:59:59"])->sum('revenue');
                    $total_miles = Detail::whereBetween('put_date', [$fromdate, $todate . " 23:59:59"])->sum('mile');
                    $total_dhmiles = Detail::whereBetween('put_date', [$fromdate, $todate . " 23:59:59"])->sum('dho');
                    $driver_count = Detail::whereBetween('put_date', [$fromdate, $todate . " 23:59:59"])->groupBy('driver_id')->count();
                }
                $temp['data'][] = ($data != null) ? $data : 0;
                $temp['total_revenue'][] = ($total_revenue != null) ? number_format($total_revenue, 2, '.', ',') : 0;
                $temp['total_miles'][] = ($total_miles != null) ? number_format(($total_miles + $total_dhmiles), 2, '.', ',') : 0;
                $temp['driver_count'][] = ($driver_count != null) ? $driver_count : 0;
                $temp['avg_dhrpm'][] = (($total_miles + $total_dhmiles) != 0) ? number_format($total_revenue / ($total_miles + $total_dhmiles), 2, '.', ',') : 0;
                $temp['avg_revenue'][] = ($data != 0) ? number_format($total_revenue / $data, 2, '.', ',') : 0;
            }
        }
        return response()->json($year_data, 200);
    }

    public function period_broker_get(Request $request)
    {
        //weekly and monthly data
        $fromdate = $request['fromdate'];
        $todate = $request['todate'];

        $employees = User::where('role', 3)->get();
        foreach ($employees as $emp) {
            $drivers = Driver::select('id')->whereRaw("find_in_set($emp->id,employee_id)")->get();
            if (count($drivers) != 0) {
                foreach ($drivers as $driver) {
                    $driver_arr[$emp->id][] = $driver->id;
                }

                if ($fromdate != "") {

                    $total_detail = Detail::whereBetween('put_date', [$fromdate, $todate . " 23:59:59"])->whereIn('driver_id', $driver_arr[$emp->id])->get()->count();
                    $total_revenue = Detail::whereBetween('put_date', [$fromdate, $todate . " 23:59:59"])->whereIn('driver_id', $driver_arr[$emp->id])->sum('revenue');
                    $total_miles = Detail::whereBetween('put_date', [$fromdate, $todate . " 23:59:59"])->whereIn('driver_id', $driver_arr[$emp->id])->sum('mile');
                    $total_dhmiles = Detail::whereBetween('put_date', [$fromdate, $todate . " 23:59:59"])->whereIn('driver_id', $driver_arr[$emp->id])->sum('dho');
                    // $driver_count=count( $driver_arr[$emp->id]);
                } else {
                    $total_detail = Detail::whereIn('driver_id', $driver_arr[$emp->id])->get()->count();
                    $total_revenue = Detail::whereIn('driver_id', $driver_arr[$emp->id])->sum('revenue');
                    $total_miles = Detail::whereIn('driver_id', $driver_arr[$emp->id])->sum('mile');
                    $total_dhmiles = Detail::whereIn('driver_id', $driver_arr[$emp->id])->sum('dho');
                    // $driver_count=count( $driver_arr[$emp->id]);
                }


                $employee = User::find($emp->id);
                $detail_array[] = array(
                    'employee_name' => $employee->firstname . " " . $employee->lastname,
                    'driver_array' => $driver_arr[$emp->id],
                    'detail_num' => ($total_detail != null) ? number_format($total_detail, 2, '.', ',') : 0,
                    'total_revenue' => ($total_revenue != null) ? number_format($total_revenue, 2, '.', ',') : 0,
                    'avg_dhrpm' => (($total_miles + $total_dhmiles) != 0) ? number_format($total_revenue / ($total_miles + $total_dhmiles), 2, '.', ',') : 0,
                    'total_miles' => ($total_miles != null) ? number_format(($total_miles + $total_dhmiles), 2, '.', ',') : 0,
                    'avg_revenue' => ($total_detail != 0) ? number_format($total_revenue / $total_detail, 2, '.', ',') : 0
                );
            }
        }
        $total_detail_broker =  $detail_array;
        $result['status'] = "ok";
        $result['detail_info_broker'] = $total_detail_broker;
        return response()->json($result, 200);
    }
}
