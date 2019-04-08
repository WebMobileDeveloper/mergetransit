<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

//  FRONT END ROUTES
Route::get('/', 'HomeController@index');
Route::get ('/blog','BlogController@index');
Route::get ('/blog/detail/{id}','BlogController@detail');

// 
Route::get("/logout", "Auth\LoginController@logout");


Route::get('change-password', function() {return view('change-password'); });
Route::post('change-password', 'Auth\UpdatePasswordController@update');


Route::get('/forgotpassword', function() {return view('forgotpassword'); });
Route::post('/forgotpassword', 'Auth\UpdatePasswordController@reset');

Route::get('/resetpassword', function() {return view('resetpassword'); });
Route::post('/resetpassword', 'Auth\UpdatePasswordController@new_password');

Route::get('thankyou', function() {return view('thankyou'); });

Route::get("/pdf/{pdf}", "PdfController@show");


// All routes in the group are protected, only authed user are allowed to access them

Route::post("/sadmin/login", "Auth\LoginController@login");
Route::get('/sadmin', 'Sadmin\AdminController@showAdminLoginForm');
Route::post('/sadmin', 'Auth\LoginController@login');


Route::get('/sadmin/register', 'Sadmin\AdminController@showAdminRegistrationForm');
Route::post('/sadmin/register', 'Auth\RegisterController@register');

Route::group(['middleware' => 'auth'], function () { 
   // Customer router
  
  Route::get('/sadmin/logout', 'Sadmin\AdminController@logout');
  Route::get("sadmin/home", 'Sadmin\HomeController@index');//
  Route::get("sadmin/total/",'Sadmin\HomeController@total_view');
  Route::get("sadmin/yearly_get/",'Sadmin\HomeController@yearly_data_get');
  Route::get("sadmin/period_broker_get/",'Sadmin\HomeController@period_broker_get');


  //drivers management in customer section page
  Route::resource('sadmin/drivers', 'Sadmin\DriverController');
  Route::get('sadmin/drivers/delete/{id}', 'Sadmin\DriverController@destroy');
  Route::post('sadmin/drivers/setactive', 'Sadmin\DriverController@setactive');


  Route::get('sadmin/changepw', 'Sadmin\UpdatePWController@index');
  Route::post('sadmin/changepw', 'Sadmin\UpdatePWController@update');

  Route::get('sadmin/account', 'Sadmin\AccountController@index');
  Route::post('sadmin/account/', 'Sadmin\AccountController@update');

  Route::get('sadmin/service', 'Sadmin\AccountController@service');
  Route::post('sadmin/service/', 'Sadmin\AccountController@service_update');

  
  //details management in customer section page
  Route::resource('sadmin/details', 'Sadmin\DetailController');
  Route::post('sadmin/get_miles', 'Sadmin\DetailController@get_miles');
  Route::post('sadmin/delete_detail_address', 'Sadmin\DetailController@delete_detail_address');

  Route::get('sadmin/details/delete/{id}', 'Sadmin\DetailController@destroy');
  Route::get('sadmin/delete_file/{id}/{no}','Sadmin\DetailController@delete_file');

  //contact list management in customer section page
  Route::resource('sadmin/contactlist', 'Sadmin\ContactController');
  Route::get('sadmin/contactlist/delete/{id}', 'Sadmin\ContactController@destroy');


  Route::get('sadmin/reports','Sadmin\ReportController@index');
  Route::post('sadmin/reports','Sadmin\ReportController@generate');

  //Cost center

  Route::get('sadmin/costreports', 'Sadmin\CostCenterController@index');
  Route::post('sadmin/costreports_generate', 'Sadmin\CostCenterController@generate');

  Route::get('sadmin/fixedcost/{seldate}', 'Sadmin\FixedCostController@index');
  Route::get('sadmin/fixedcost', 'Sadmin\FixedCostController@index');
  Route::post('sadmin/fixedcost', 'Sadmin\FixedCostController@store');

  Route::get('sadmin/loadexpense','Sadmin\LoadExpenseController@index');
  Route::get('sadmin/loadexpense/{detail_id}', 'Sadmin\LoadExpenseController@index');
  Route::post('sadmin/loadexpense','Sadmin\LoadExpenseController@store');

  Route::get('sadmin/maintenance','Sadmin\MaintenanceController@index');
  Route::get('sadmin/maintenance/{seldate}/{id}', 'Sadmin\MaintenanceController@index');
  Route::post('sadmin/maintenance', 'Sadmin\MaintenanceController@store');


  //billing management in customer section page
  Route::get('sadmin/billing', 'Sadmin\BillingController@index');
  Route::get('sadmin/billing/create/{id}', 'Sadmin\BillingController@create_invoice');
  Route::post('sadmin/set_paymentmark', 'Sadmin\BillingController@set_paymentmark');
  Route::post('sadmin/generate_invoice', 'Sadmin\BillingController@generate_invoice');

  Route::post('sadmin/send_invoice', 'Sadmin\BillingController@send_invoice');
  
  Route::resource('sadmin/currentcustomer', 'Sadmin\CurrentCustomerController');
  Route::get('sadmin/currentcustomer/delete/{id}/{section}', 'Sadmin\CurrentCustomerController@destroy');

  Route::get('sadmin/profit_report', 'Sadmin\ProfitReportController@index');

  Route::get('sadmin/find_parent','Sadmin\DriverController@find_parent');


});




// Admin routes
Route::post("/admin/login", "Auth\LoginController@login");
Route::get('/admin', 'Admin\AdminController@showAdminLoginForm');
Route::post('/admin', 'Auth\LoginController@login');
Route::group(['middleware' => 'auth'], function () { 
  
  Route::get('/admin/logout', 'Admin\AdminController@logout');
  Route::get("admin/home", 'Admin\HomeController@index');//
  Route::get("admin/total/",'Admin\HomeController@total_view');
  Route::get("admin/yearly_get/",'Admin\HomeController@yearly_data_get');
  Route::get("admin/period_broker_get/",'Admin\HomeController@period_broker_get');



  //admin user role
  Route::resource('admin/roles', 'Admin\RoleController');
  Route::get('admin/roles/delete/{id}', 'Admin\RoleController@destroy');

  Route::resource('admin/users', 'Admin\UserController');
  Route::get('admin/users/delete/{id}', 'Admin\UserController@destroy');
  Route::post('admin/users/setactive', 'Admin\UserController@setactive');

  Route::resource('admin/contactlist', 'Admin\ContactController');
  Route::get('admin/contactlist/delete/{id}', 'Admin\ContactController@destroy');

  Route::resource('admin/customers', 'Admin\CustomerController');
  Route::get('admin/customers/delete/{id}', 'Admin\CustomerController@destroy');
  Route::post('admin/customers/setactive', 'Admin\CustomerController@setactive');
  Route::get('admin/customer_delete_file/{id}/{no}','Admin\CustomerController@delete_file');

  Route::resource('admin/drivers', 'Admin\DriverController');
  Route::get('admin/drivers/delete/{id}', 'Admin\DriverController@destroy');
  Route::post('admin/drivers/setactive', 'Admin\DriverController@setactive');
  Route::get('admin/employee_show','Admin\DriverController@employee_show');


  Route::resource('admin/currentcustomer', 'Admin\CurrentCustomerController');
  Route::get('admin/currentcustomer/delete/{id}/{section}', 'Admin\CurrentCustomerController@destroy');

  Route::resource('admin/details', 'Admin\DetailController');
  Route::post('admin/get_miles', 'Admin\DetailController@get_miles');
  Route::post('admin/delete_detail_address', 'Admin\DetailController@delete_detail_address');

  Route::get('admin/details/delete/{id}/{section}', 'Admin\DetailController@destroy');
  Route::get('admin/delete_file/{id}/{no}','Admin\DetailController@delete_file');


  Route::get('admin/reports','Admin\ReportController@index');
  Route::post('admin/reports','Admin\ReportController@generate');


  Route::get('admin/invoice','Admin\InvoiceController@index');
  Route::get('admin/invoice/{id}/create_invoice','Admin\InvoiceController@create_invoice');
  Route::post('admin/create_invoice','Admin\InvoiceController@store_invoice');
  Route::post('admin/update_invoice','Admin\InvoiceController@update_invoice');
  Route::get('admin/invoice/delete/{id}', 'Admin\InvoiceController@destroy');
  
  Route::post('admin/send_invoice','Admin\InvoiceController@send_invoice');
  Route::get('admin/invoice/{id}/view_detail','Admin\InvoiceController@view_detail');
  Route::post('admin/set_payment','Admin\InvoiceController@set_payment');

  Route::get('admin/invoice/{id}/view_invoice','Admin\InvoiceController@view_invoice');
  Route::get('admin/invoice/{id}/invoice_edit','Admin\InvoiceController@invoice_edit');

  Route::get('admin/cleanusers', 'Admin\UserController@cleanusers');

});
Route::get ('seturl','BillingController@setUrl');
Route::post("mailsend", "MailController@send");


//Driver report router
Route::get('report','ReportController@index');//report page
Route::post('report','ReportController@generate');
Route::get('billing','BillingController@index'); //billing page
Route::get('download-PDF/{id}', 'BillingController@pdf_download');


Route::get('show_invoice/{id}','BillingController@show_invoice');
Route::any('/{pageUrl?}', 'HomeController@gotoTarget');
