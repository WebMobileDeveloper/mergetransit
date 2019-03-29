<?php

namespace App\Http\Controllers\API;

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
use File;


class BillingController extends Controller
{
    public $successStatus = 200;
    public function __construct()
    {
       //$this->middleware('auth');
       
    }

    public function index(Request $request) {
        dd($request);
        
    }
    public function adddrivers(Request $request)
    {      
        
        return response()->json(['data'=>'success'], $this->successStatus);
    }
}
