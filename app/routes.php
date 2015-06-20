<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('hello');
});


Route::group(array('prefix' => 'v1'), function()
{
	Route::get('states/{state}/cities','StatesController@cities');
	Route::get('states/{state}/cities/{city}','StatesController@citiesinradius');	
	
	Route::get('users/{user}/visits','UsersController@visits');
	Route::post('users/{user}/visits','UsersController@storevisits');

});

App::missing(function($exception) { 
    return Response::make('Invalid Request', 400); 
});
