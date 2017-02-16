<?php
use GuzzleHttp\Client as GuzzleClient;
use Bican\Roles\Models\Role;
/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['middleware' => ['web']], function () {

	Route::get('/', function() {
	    return redirect('exchange');
	});
	Route::get('exchange', function() {
		return view('exchange');
	});
	

	Route::get('tos', function() {
		return view('tos');
	});
	Route::get('contact', function() {
		return view('contact');
	});

	Route::get('roles', function() {
		$user = App\User::find(3);
		$user->attachRole(1);
	});

	Route::controller('admin', 'AdminController');

	Route::get('lang/{lang}', ['as' => 'lang.switch', 'uses' => 'LanguageController@switchLang']);

	Route::controller('steam', 'SteamController');
	Route::controller('price', 'PriceController');
	//Route::controller('steam-market', 'SteamMarketController');

	Route::controller('settings', 'SettingsController');
	Route::controller('auth', 'AuthController');

	Route::group(['prefix' => 'api', 'namespace' => 'Api'], function() {
		Route::group(['prefix' => 'v1'], function() {
			Route::resource('price', 'PriceController');
		});
	});


});
