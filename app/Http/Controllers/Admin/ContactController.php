<?php

namespace App\Http\Controllers\Admin;

use App\Contact_list;
use App\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class ContactController extends Controller
{
    public function __construct()
    {
       $this->middleware('auth');
    }
    public function index(Request $request)
    {
        $s = $request->s;
        $contactlist = Contact_list::select('*');
        if(isset($s))$contactlist -> search($s); 
        $contactlist=$contactlist->orderBy('d_company_name') ; 
        $contactlist = $contactlist->paginate(10);
        
        return view('admin.contactlist.index',compact('contactlist','s'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.contactlist.contactlistAdd');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $checkfield = Contact_list::where('d_company_name', $request['d_company_name'])->get();
        if (count($checkfield)>0) {
            return redirect()->back()->withInput($request->all())->with("status", "This customer has been already registered. pleaes input unique company name.");
        } else {

            $contact= new Contact_list();
            $contact->d_company_name= $request['d_company_name'];
            $contact->address1 = Input::get('address1');
            $contact->address2 = Input::get('address2');
            $contact->city= $request['city'];
            $contact->state= $request['state'];
            $contact->zipcode= $request['zipcode'];
            $contact->save();
            return redirect('admin/contactlist');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect('admin/contactlist');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $contact = Contact_list::find($id);
        return view('admin.contactlist.contactlistEdit',compact('contact'));
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
        $contact = Contact_list::find($id);
        $check_companyname = $contact->d_company_name;
        if (trim($request['d_company_name']) != $check_companyname) {

            $check = Contact_list::where('d_company_name', $request['d_company_name'])->get();
            if (count($check)>0) {
                return redirect()->back()->withInput(compact('contact'))->with("status", "This customer has been already registered. pleaes input unique company name.");
                die();
            }
        } 
       
        $contact->d_company_name = Input::get('d_company_name');
        $contact->address1 = Input::get('address1');
        $contact->address2 = Input::get('address2');
        $contact->city = Input::get('city');
        $contact->state = Input::get('state');
        $contact->zipcode = Input::get('zipcode');    
        $contact->save();
        return redirect('admin/contactlist');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contactlist = Contact_list::find($id);
        $contactlist->delete();

        return redirect('admin/contactlist');
    }
}
