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
Route::get('/','HomeController@index');
Route::get("/logout","Auth\LoginController@logout");
Route::get("/pdf","PdfController@createpdf");


Route::get('/sanjay',function(){
	return "Sanjay";
});


// All routes in the group are protected, only authed user are allowed to access them
Route::group(['middleware' => 'auth'], function() {

  
Route::resource('/itemCRUD','ItemCRUDController');

Route::resource('/driverCRUD','DriverCRUDController');
  //  Route::get('restaurants/admins/{id}', 'RestaurantsController@admins');

});




// Admin routes
Route::post("/admin/login","Auth\LoginController@login");
Route::get('/admin','Admin\AdminController@showAdminLoginForm');
Route::post('/admin','Auth\LoginController@login');
Route::get("admin/home",'Admin\HomeController@index');
//
Route::post("mailsend","MailController@send");

Route::get('/{pageUrl}','HomeController@gotoTarget');


