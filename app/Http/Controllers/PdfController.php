<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use PDF;
use Auth;
class PdfController extends Controller
{
    //
public function index()
{
return view('pdfcreate');
	
}

public function show()
{
	
	return "Reports View";
}

    public function create()
    {
    	$name ='MergeTranistReport.pdf';
		$date = date('Y-m-d H:i:s');
		$filename = $date.$name;
		//$cid	=Auth::user()->Driver_ID;
		$cemail = Auth::user()->email;

		$sdate='2017/01/03';
		$edate='2017/08/28';

		 //$items = DB::table('dispatch_details');
		//$pdf = PDF::loadView('pdf',['items'=>$items]);
		//return $pdf->download($filename);
		//return "Agnani SANJAY";
		//return view('pdf')->with('items',$items);
		//return $items;



		//$results = DB::select('select * from users where id = :id', ['id' => 1]);

		$items = DB::select('SELECT * FROM `dispatch_details` WHERE `email` =:id and  `Pdate` between :sdate AND :edate', ['id' =>$cemail,'sdate'=>$sdate,'edate'=>$edate]);
		
		//$items = DB::select('SELECT * FROM `dispatch_details` WHERE `Driver_ID` ="$cid"');
		//$items = DB::select(' SELECT * FROM `dispatch_details` WHERE Company ="TQL"');
		$pdf = PDF::loadView('pdf',['items'=>$items]);
		return view('pdf')	->with('items',$items);
		
		return $pdf->setPaper('a4', 'landscape')->download($filename);
		
		
		
    }



public function store(Request $request)
    {
		$data = $request->all(); // this grabs all my request data - great!
		
		
		$name ='MergeTranistReport.pdf';
		$date = date('Y-m-d H:i:s');
		$filename = $date.$name;
		//$cid	=Auth::user()->Driver_ID;
		$cemail = Auth::user()->email;
		$sdate=$request->sdate;
		$edate=$request->edate;
	
	
		$items = DB::select('SELECT * FROM `dispatch_details` WHERE `email` =:id and  `Pdate` between :sdate AND :edate', ['id' =>$cemail,'sdate'=>$sdate,'edate'=>$edate]);
		$pdf = PDF::loadView('reports',['items'=>$items]);
		//return view('pdf')->with('items',$items);
	    
		//return view('reports')->with('items',$items);
	    return $pdf->setPaper('a4', 'landscape')->download($filename);
	
    }

}
