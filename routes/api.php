<?php

use App\Country;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
	return $request->user();
});

Route::group(['middleware' => 'auth:api'], function(){

	Route::post('me', 'Api\UserController@me');
	Route::get('placestypes','Api\UserController@placesTypes');
	Route::post('addplace', 'Api\UserController@addPlace');
	Route::get('notifications', 'Api\UserController@notifications');
	Route::post('rate', 'Api\UserController@rate');
	Route::get('addtofavourites', 'Api\UserController@addToFavourites');
	Route::get('myfavouritelist', 'Api\UserController@myFavouriteList');
	Route::post('addpost', 'Api\UserController@addPost');

	Route::post('refresh', 'Api\UserController@refresh');
	Route::post('editprofile', 'Api\UserController@editProfile');
	Route::get('logout','Api\UserController@logout');
});

Route::post('login','Api\PublicController@login')->name('login');
Route::post('signup','Api\PublicController@signup');

Route::get('intro','Api\PublicController@intro');
Route::get('aboutus','Api\PublicController@aboutus');
Route::get('sliders','Api\PublicController@sliders');
Route::get('continents','Api\PublicController@continents');
Route::get('countries','Api\PublicController@countries');
Route::get('places','Api\PublicController@places');
Route::post('findplace','Api\PublicController@searchPlace');
Route::get('findcountry','Api\PublicController@searchCountry');
Route::post('sendcontactus','Api\PublicController@sendContactUs');
Route::get('contactlinks','Api\PublicController@contactLinks');
Route::get('guidebooks','Api\PublicController@guideBooks');
Route::get('offers','Api\PublicController@offers');
Route::get('externalLinks','Api\PublicController@externalLinks');
Route::get('ambassadors','Api\PublicController@ambassadors');


Route::get('testFCM','Api\PublicController@testFCM');
