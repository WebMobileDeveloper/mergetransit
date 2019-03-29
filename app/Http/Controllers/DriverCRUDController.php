<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\driverdetail;

class DriverCRUDController extends Controller

{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		return view('DriverCRUD.create');
		return "INDEX METHOD OF DRIVER_CRUD";
		
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      //return view('welcome');
		//return "Create methos for driver CRUD ";
		return view('DriverCRUD.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
   
   
    public function store(Request $request)
    {
		$data = $request->all(); // this grabs all my request data - great!
		$user = new Driverdetail;
		//$user->Email=$request->Email; // insert ALL posted data
		//$user->Contact=$request->Contact;
		
        $user->fill($request->all());
        $user->save();

        return redirect()->route('driverCRUD.index')
                       ->with('success','Item created successfully');
		  
		// return $request;
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
