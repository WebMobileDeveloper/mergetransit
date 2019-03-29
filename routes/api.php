<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('api_login','API\AuthController@login');
Route::post('api_signup','API\AuthController@signup');
Route::post('api_logout','API\AuthController@logout');
Route::post('api_emailstatus','API\AuthController@emailstatus');
Route::post('api_forgotpass','API\AuthController@forgotpass');
Route::post('api_forgotupdate','API\AuthController@forgotupdate');


/*CleanAPP */
Route::post('api_getzipcode','API\ComplexController@get_zipcode');


Route::group(['middleware' => 'auth:api'], function(){
    Route::post('api_report_generate','API\ReportController@generate');
    Route::post('api_report_calendar','API\ReportController@calendar');
    Route::post('api_imageupload','API\ReportController@fileupload');
    Route::post('api_detailsdata','API\ReportController@detailsdata');    
    Route::post('api_getDetailToday','API\ReportController@getDetailData');
    Route::post('api_detailsinvoice','API\ReportController@detailsinvoice');
    Route::post('api_delete_file','API\ReportController@delete_file');
    Route::post('api_phonenumber','API\ReportController@phonenumber');
    Route::post('api_customerupdate','API\ReportController@customerupdate');
    Route::post('api_customerimagedelete','API\ReportController@customerfiledelete');
    Route::post('api_changepassword','API\ReportController@changepassword');
    Route::post('api_memberupgrade','API\ReportController@memberupgrade');
    Route::post('api_imageuploadcustome','API\ReportController@customerupload');
    Route::post('api_invoice','API\InvoiceController@index');
    Route::post('api_adddrivers','API\AddDriversController@adddrivers');
    Route::post('api_invoice_create','API\InvoiceController@invoice_create');
    Route::post('api_payment_detail','API\InvoiceController@payment_made');
    
    /*-----------------cost center api router----------------------------*/
    //Mian Cost center Router
   
    Route::post('api_get_cost_reports', 'API\CostCenterController@get_rows_by_range');
    Route::post('api_cost_detail', 'API\CostCenterController@get_report_detail');
    
    Route::post('api_cost_delete', 'API\CostCenterController@delete_cost_detail');
    Route::post('api_costimage_delete', 'API\CostCenterController@delete_image');
    //Fixed cost Routers
    Route::post('api_fixedcost_get','API\FixedCostController@index');
    Route::post('api_fixedcost_store','API\FixedCostController@store');
    Route::post('api_fixedcost_update','API\FixedCostController@update');
    
    //Maintenance cost Routers
    Route::post('api_maintenance_store','API\MaintenanceController@store');
    Route::post('api_maintenance_update','API\MaintenanceController@update');
    Route::post('api_maintenace_imagestore','API\MaintenanceController@upload_image');
    
    //Load Expense Routers
    Route::post('api_loadexpense_store','API\LoadExpenseController@store');
    Route::post('api_loadexpense_update','API\LoadExpenseController@update');
    Route::post('api_loadexpense_imagestore','API\LoadExpenseController@upload_image');
   
});

Route::post('stripe_hook', 'API\BillingController@index');


    
