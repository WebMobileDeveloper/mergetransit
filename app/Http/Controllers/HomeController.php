<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

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
        return view('index');
		
    }


    public function gotoTarget($url) {
     
        if(view()->exists($url)) {
            return view($url)->render();
        } else {
            return view('404page')->render();
        }
        
    }

  
}
