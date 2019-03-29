<?php

namespace App\Http\Controllers\API;

use App\Customer;
use App\Driver;
use App\Fixed_cost;
use App\Http\Controllers\Controller;
use App\Load_expense;
use App\Maintenance;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CostCenterController extends Controller
{
    public $successStatus = 200;

    public function index()
    {

    }

    public function get_rows_by_range(Request $request)
    {

        $startdate = $request['startdate'];
        $enddate = $request['enddate'];

        $from = $this->setstartDate($startdate);
        $to = $this->setendDate($enddate);

        $driverID = $request['driver_id'];
        $user = Auth::user();

        if ($user->role == 4) {
            $customer = Customer::join('users', 'users.email', '=', 'customers.email')
                ->select('customers.id', 'customers.company', 'customers.firstname', 'customers.lastname', 'customers.phone', 'customers.image_path', 'customers.description')
                ->where('users.email', Auth::user()->email)->get();

            //if (empty($driverID)) {
            $where_m = "WHERE customer_id=" . $customer[0]->id;
            $where_l = "WHERE load_expenses.customer_id=" . $customer[0]->id;

            $costinfo = $this->customer_costinfo($from, $to, null);
            //} else {
            //    $where_m = "WHERE driver_id=". $driverID;
            //    $where_l = "WHERE load_expenses.driver_id=". $driverID;

            //    $costinfo = $this->driver_costinfo( $from, $to,  $driverID);

            //}
        } else {
            $driver = Driver::join('customers', 'customers.id', 'drivers.company_id')
                ->join('users', 'users.id', 'drivers.user_id')
                ->select('drivers.id', 'drivers.company_id', 'drivers.employee_id', 'users.firstname', 'users.lastname', 'customers.company', 'customers.id AS customersid')
                ->where('drivers.user_id', Auth::user()->id)->get();
            if (empty($driverID)) {
                $where_m = " WHERE driver_id=" . $driver[0]->id;
                $where_l = " WHERE load_expenses.driver_id=" . $driver[0]->id;
                $costinfo = $this->driver_costinfo($from, $to, $driver[0]->id);
            } else {
                $where_m = " WHERE driver_id=" . $driverID;
                $where_l = " WHERE load_expenses.driver_id=" . $driverID;
                $costinfo = $this->driver_costinfo($from, $to, $driverID);
            }

        }

        $where2 = " AND date>='" . $from . "' AND date<='" . $to . "'";

        $sqlQuery = "SELECT
                        id,
                        date,
                        driver_id,
                        'maintenance' as tabletype,
                        purpose as s_Purpose,
                        cost as s_Cost,
                        cost as total,
                        description as s_Description,
                        file_path,
                        file_name

                    FROM maintenances "
            . $where_m . $where2;
        $result = DB::select($sqlQuery);

        $sqlQuery_load = "SELECT
                load_expenses.id,
                load_expenses.date,
                load_expenses.driver_id,
                'load_expense' as tabletype ,
                details.po as s_PO,
                contact_lists.d_company_name as s_Company,
                load_expenses.fuel as s_Fuel,
                load_expenses.gallons as s_Gallons,
                load_expenses.def as s_DEF,
                load_expenses.parking as s_Parking,
                load_expenses.payroll as s_Payroll,
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
            . $where_l . $where2;
        $load_result = DB::select($sqlQuery_load);

        if (!empty($load_result)) {
            foreach ($load_result as $value) {
                array_push($result, $value);
            }
        }

        usort($result, function ($a, $b) {
            return strtotime($a->date) - strtotime($b->date);
        });

        return response()->json(['data' => $result, 'costinfo' => $costinfo], $this->successStatus);

    }

    public function get_report_detail(Request $request)
    {
        $tabletype = $request['tabletype'];
        $id = $request['id'];

        switch ($tabletype) {

            case 'load_expense':
                $reportdata = Load_expense::join('details', 'details.id', '=', 'load_expenses.detail_id')->join('contact_lists', 'contact_lists.id', '=', 'details.contact_id')->
                    select(
                    'load_expenses.id',
                    'load_expenses.date as s_Date',
                    'details.po as s_PO',
                    'contact_lists.d_company_name as s_Company',
                    'load_expenses.fuel as s_Fuel',
                    'load_expenses.gallons as s_Gallons',
                    'load_expenses.def as s_DEF',
                    'load_expenses.parking as s_Parking',
                    'load_expenses.tolls as s_Tolls',
                    'load_expenses.tolls_txt as toll_txt',
                    'load_expenses.lumper as s_Lumper',
                    'load_expenses.lumper_txt',
                    'load_expenses.accomerdations as s_Hotel',
                    'load_expenses.payroll as s_Payroll',
                    'load_expenses.accom_txt',
                    'load_expenses.other as s_Other',
                    'load_expenses.other_txt',
                    'load_expenses.file_name',
                    'load_expenses.file_path'

                )
                    ->where('load_expenses.id', $id)->get();
                break;
            case 'maintenance':
                $reportdata = Maintenance::
                    select(
                    'id',
                    'driver_id',
                    'date as s_Date',
                    'purpose as s_Purpose',
                    'cost as s_Cost',
                    'description as s_Description',
                    'file_path',
                    'file_name'
                )
                    ->where('id', $id)->get();
                break;
            default:
                return response()->json(['data' => 'failed'], $this->successStatus);
                break;
        }

        if (isset($reportdata)) {
            return response()->json(['data' => $reportdata[0]], $this->successStatus);
        } else {

            return response()->json(['data' => 'failed'], $this->successStatus);
        }

    }

    public function delete_cost_detail(Request $request)
    {
        $tabletype = $request['tabletype'];
        $id = $request['id'];

        switch ($tabletype) {

            case 'load_expense':
                $reportdata = Load_expense::find($id);
                break;
            case 'maintenance':
                $reportdata = Maintenance::find($id);
                break;
            default:
                return response()->json(['data' => 'failed'], $this->successStatus);
                break;
        }

        $reportdata->delete();

        $total_data = $this->get_costinfo();
        return response()->json(['data' => "Success", 'costinfo' => $total_data], $this->successStatus);
    }

    public function delete_image(Request $request)
    {
        $id = $request['id'];
        $no = $request['fileNo'];
        $tabletype = $request['tabletype'];

        switch ($tabletype) {

            case 'load_expense':
                $imagedata = Load_expense::find($id);
                $files_arr = explode(",", $imagedata->file_path);
                $names_arr = explode(",", $imagedata->file_name);

                unset($files_arr[$no]);
                $refile_arr = array_values($files_arr);
                unset($names_arr[$no]);
                $rename_arr = array_values($names_arr);
                $imagedata->file_path = implode(',', $refile_arr);
                $imagedata->file_name = implode(',', $rename_arr);

                $save_status = $imagedata->save();

                $reportdata = Load_expense::join('details', 'details.id', '=', 'load_expenses.detail_id')->join('contact_lists', 'contact_lists.id', '=', 'details.contact_id')->
                    select(
                    'load_expenses.id',
                    'load_expenses.date as s_Date',
                    'details.po as s_PO',
                    'contact_lists.d_company_name as s_Company',
                    'load_expenses.fuel as s_Fuel',
                    'load_expenses.gallons as s_Gallons',
                    'load_expenses.def as s_DEF',
                    'load_expenses.parking as s_Parking',
                    'load_expenses.tolls as s_Tolls',
                    'load_expenses.tolls_txt as toll_txt',
                    'load_expenses.lumper as s_Lumper',
                    'load_expenses.lumper_txt',
                    'load_expenses.accomerdations as s_Hotel',
                    'load_expenses.accom_txt',
                    'load_expenses.other as s_Other',
                    'load_expenses.other_txt',
                    'load_expenses.file_name',
                    'load_expenses.file_path'

                )
                    ->where('load_expenses.id', $id)->get();
                break;

            case 'maintenance':
                $imagedata = Maintenance::find($id);
                $files_arr = explode(",", $imagedata->file_path);
                $names_arr = explode(",", $imagedata->file_name);

                unset($files_arr[$no]);
                $refile_arr = array_values($files_arr);
                unset($names_arr[$no]);
                $rename_arr = array_values($names_arr);
                $imagedata->file_path = implode(',', $refile_arr);
                $imagedata->file_name = implode(',', $rename_arr);

                $save_status = $imagedata->save();

                $reportdata = Maintenance::
                    select(
                    'id',
                    'driver_id',
                    'date as s_Date',
                    'purpose as s_Purpose',
                    'cost as s_Cost',
                    'description as s_Description',
                    'file_path',
                    'file_name'
                )
                    ->where('id', $id)->get();
                break;
            default:
                return response()->json(['data' => 'failed'], $this->successStatus);
                break;
        }

        if ($save_status) {
            return response()->json(['data' => $reportdata[0]], $this->successStatus);
        }
    }

    public function get_costinfo()
    {
        $user = Auth::user();
        if ($user->role == 4) {

            return $this->customer_costinfo(null, null, null);

        } else {
            return $this->driver_costinfo(null, null, null);
        }

    }

    public function customer_costinfo($startdate = null, $enddate = null, $driverID = null)
    {

        $from = $this->setstartDate($startdate);
        $to = $this->setendDate($enddate);

        $user = Auth::user();
        $customers = Customer::join('users', 'users.email', '=', 'customers.email')
            ->select('customers.id', 'customers.company', 'customers.firstname', 'customers.lastname', 'customers.phone', 'customers.image_path', 'customers.description')
            ->where('users.email', Auth::user()->email)->get();

        $reports = Driver::join('details', 'details.driver_id', '=', 'drivers.id')
            ->join('customers', 'customers.id', '=', 'drivers.company_id')
            ->where('customers.email', Auth::user()->email)->whereBetween('details.put_date', array($from, $to));

        //get cost total
        $fixed_cost = Fixed_cost::select('total')
            ->where('customer_id', $customers[0]->id)
            ->where('date', 'like', '%' . date("Y-m") . '%')
            ->get();

        $fixed_cost_y = Fixed_cost::where('customer_id', $customers[0]->id)
            ->whereBetween('date', array($from, $to))
            ->sum('total');

        $loadexpense = Load_expense::where('customer_id', $customers[0]->id)
            ->whereBetween('date', array($from, $to))
            ->sum('total');

        $maintenance = Maintenance::where('customer_id', $customers[0]->id)
            ->whereBetween('date', array($from, $to))
            ->sum('cost');

        $month = Date('m');
        $total_cost = $fixed_cost_y + $loadexpense + $maintenance;
        $totalrevenue = $reports->sum('details.revenue');
        $total_mile = $reports->sum('details.mile');
        $total_dho = $reports->sum('details.dho');

        $data['total_revenue'] = "$" . number_format($totalrevenue, 2);
        $data['total_cost'] = "$" . number_format($total_cost, 2);
        $data['total_profit'] = "$" . number_format(($totalrevenue - $total_cost), 2);
        $data['total_mile'] = number_format($total_mile);
        $data['total_dho'] = number_format($total_dho);
        $data['fixed_cost'] = (count($fixed_cost) == 0) ? "$0.00" : "$" . number_format($fixed_cost[0]->total, 2);
        $data['fixed_cost_y'] = "$" . number_format($fixed_cost_y, 2);
        $data['loadexpense_y'] = "$" . number_format($loadexpense, 2);
        $data['maintenance_y'] = "$" . number_format($maintenance, 2);

        $t_miles = $total_mile + $total_dho;
        $data['total_rpm'] = ($t_miles) ? "$" . number_format(($totalrevenue / $t_miles), 2) : 0;
        $data['cost_rpm'] = ($t_miles) ? "$" . number_format(($total_cost / $t_miles), 2) : 0;
        $data['profit_rpm'] = ($t_miles) ? "$" . number_format((($totalrevenue - $total_cost) / $t_miles), 2) : 0;
        $data['ratio'] = ($totalrevenue == 0) ? "0%" : number_format($total_cost / $totalrevenue * 100, 2) . "%";

        return $data;
    }
    public function driver_costinfo($startdate = null, $enddate = null, $driverID = null)
    {
        $from = $this->setstartDate($startdate);
        $to = $this->setendDate($enddate);

        $user = Auth::user();
        $drivers = Driver::join('customers', 'customers.id', 'drivers.company_id')
            ->join('users', 'users.id', 'drivers.user_id')
            ->select('drivers.id', 'drivers.company_id', 'drivers.employee_id', 'users.firstname', 'users.lastname', 'customers.company', 'customers.id AS customersid')
            ->where('drivers.user_id', Auth::user()->id)->get();
        if (!empty($driverID)) {
            $driver_ID = $driverID;
        } else {
            $driver_ID = $drivers[0]->id;
        }

        $reports = Driver::join('details', 'details.driver_id', '=', 'drivers.id')
            ->where('details.driver_id', $driver_ID)->whereBetween('details.put_date', array($from, $to));

        //get cost total

        $loadexpense = Load_expense::where('driver_id', $driver_ID)->whereBetween('date', array($from, $to))->sum('total');
        $maintenance = Maintenance::where('driver_id', $driver_ID)->whereBetween('date', array($from, $to))->sum('cost'); //->whereYear('date', '=', date("Y"))

        $month = Date('m');
        $total_cost = $loadexpense + $maintenance;
        $totalrevenue = $reports->sum('details.revenue');
        $total_mile = $reports->sum('details.mile');
        $total_dho = $reports->sum('details.dho');

        $data['total_revenue'] = "$" . number_format($totalrevenue, 2);
        $data['total_cost'] = "$" . number_format($total_cost, 2);
        $data['total_profit'] = "$" . number_format(($totalrevenue - $total_cost), 2);
        $data['total_mile'] = number_format($total_mile);
        $data['total_dho'] = number_format($total_dho);
        $data['fixed_cost'] = 0;
        $data['fixed_cost_y'] = 0;
        $data['loadexpense_y'] = "$" . number_format($loadexpense, 2);
        $data['maintenance_y'] = "$" . number_format($maintenance, 2);

        $t_miles = $total_mile + $total_dho;
        $data['total_rpm'] = ($t_miles) ? "$" . number_format(($totalrevenue / $t_miles), 2) : 0;
        $data['cost_rpm'] = ($t_miles) ? "$" . number_format(($total_cost / $t_miles), 2) : 0;
        $data['profit_rpm'] = ($t_miles) ? "$" . number_format((($totalrevenue - $total_cost) / $t_miles), 2) : 0;
        $data['ratio'] =  ($totalrevenue == 0) ? "0%" : number_format($total_cost / $totalrevenue * 100, 2) . "%";

        return $data;

    }

    // $from = $this->setstartDate($startdate);
    //     $to   = $this->setendDate($enddate);
    public function setstartDate($date)
    {
        if (empty($date)) {
            $date = date('Y-m-d', strtotime('-1 year', strtotime(date('Y-m-d'))));

        }

        return $date;
    }

    public function setendDate($date)
    {
        if (empty($date)) {
            $date = date('Y-m-d', strtotime('1 day', strtotime(date('Y-m-d'))));
        }

        return $date;
    }

}
