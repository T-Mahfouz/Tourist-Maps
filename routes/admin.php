<?php

use Illuminate\Http\Request;







view()->share('resource',url('').'/');

Route::group(['prefix' => 'admin'],function(){
	
	Route::get('home','Admin\AdminController@home')->name('admin-home');
	
	######## Admins
	Route::get('admins','Admin\AdminController@admins')->name('admin-admins');
	Route::post('deleteadmin','Admin\AdminController@deleteAdmin')->name('admin-delete-admin');
	Route::post('activateAdmin','Admin\AdminController@activateAdmin')->name('admin-change-admin-status');
	Route::post('editadmin','Admin\AdminController@editAdmin')->name('admin-edit-admin');
	Route::post('addadmin','Admin\AdminController@addAdmin')->name('admin-add-admin');

	Route::post('editMe','Admin\AdminController@editMe')->name('admin-edit-admin-profile');

	######## Users
	Route::get('users','Admin\AdminController@users')->name('admin-users');
	Route::post('deleteuser','Admin\AdminController@deleteUser')->name('admin-delete-user');
	Route::post('deleteuserpost','Admin\AdminController@deleteUserPost')->name('admin-delete-user-post');
	Route::post('activateuser','Admin\AdminController@activateUser')->name('admin-change-user-status');
	Route::post('editUser','Admin\AdminController@editUser')->name('admin-edit-user');
	Route::post('addUser','Admin\AdminController@addUser')->name('admin-add-user');
	Route::post('sendNotifications','Admin\AdminController@sendNotifications')->name('admin-send-notifications');
	


	######## Continents
	Route::match(['get','post'],'continents','Admin\AdminController@continents')->name('admin-continents');
	Route::post('add-continent','Admin\AdminController@addContinent')->name('admin-add-continent');
	Route::post('edit-continent','Admin\AdminController@editContinent')->name('admin-edit-continent');
	Route::post('delete-continent','Admin\AdminController@deleteContinent')->name('admin-delete-continent');

	######## countries
	Route::get('countries','Admin\AdminController@countries')->name('admin-countries');
	Route::post('addcountry','Admin\AdminController@addCountry')->name('admin-add-country');
	Route::post('deletecountry','Admin\AdminController@deleteCountry')->name('admin-delete-country');
	Route::post('editcountry','Admin\AdminController@editCountry')->name('admin-edit-country');

	######## Places
	Route::match(['get','post'],'places','Admin\AdminController@places')->name('admin-places');
	Route::post('addplace','Admin\AdminController@addPlace')->name('admin-add-place');
	Route::post('changeplacestatus','Admin\AdminController@changePlaceStatus')->name('admin-change-place-status');
	Route::post('editplace','Admin\AdminController@editPlace')->name('admin-edit-place');
	Route::post('deleteplace','Admin\AdminController@deletePlace')->name('admin-delete-place');
	Route::post('editPlace','Admin\AdminController@editPlace')->name('admin-edit-place');
	Route::post('addPlace','Admin\AdminController@addPlace')->name('admin-add-place');
	Route::post('deletePlaceImage','Admin\AdminController@deletePlaceImage')->name('admin-delete-place-image');
	
	######## PlacesTypes
	Route::match(['get','post'],'placestypes','Admin\AdminController@placesTypes')->name('admin-placestypes');
	Route::post('editplacetype','Admin\AdminController@editPlaceType')->name('admin-edit-placetype');
	Route::post('addplacetype','Admin\AdminController@addPlaceType')->name('admin-add-placetype');
	Route::post('deleteplacetype','Admin\AdminController@deletePlaceType')->name('admin-delete-placetype');


	######## About Us
	Route::get('aboutus','Admin\AdminController@aboutus')->name('admin-aboutus');
	Route::post('editaboutus','Admin\AdminController@editAboutus')->name('admin-edit-aboutus');

	######## Intro
	Route::match(['get','post'],'intro','Admin\AdminController@intro')->name('admin-intro');
	Route::post('edit-intro','Admin\AdminController@editIntro')->name('admin-edit-intro');


	######## Sliders
	Route::match(['get','post'],'sliders','Admin\AdminController@sliders')->name('admin-sliders');
	Route::post('edit-slider','Admin\AdminController@editSlider')->name('admin-edit-slider');
	Route::post('delete-slider','Admin\AdminController@deleteSlider')->name('admin-delete-slider');
	Route::post('add-slider','Admin\AdminController@addSlider')->name('admin-add-slider');



	######## Contacts
	Route::match(['get','post'],'contactlinks','Admin\AdminController@contactlinks')->name('admin-contactlinks');
	Route::post('editcontactLink','Admin\AdminController@editContactLink')->name('admin-edit-contactLink');
	Route::post('deletecontactLink','Admin\AdminController@deleteContactLink')->name('admin-delete-contactLink');
	Route::post('addcontactLink','Admin\AdminController@addContactLink')->name('admin-add-contactLink');


	######## External Links
	Route::match(['get','post'],'externallinks','Admin\AdminController@externalLinks')->name('admin-externallinks');
	Route::post('editexternalLink','Admin\AdminController@editextErnalLink')->name('admin-edit-externalLink');
	Route::post('deleteexternalLink','Admin\AdminController@deleteExternalLink')->name('admin-delete-externalLink');
	Route::post('addexternalLink','Admin\AdminController@addExternalLink')->name('admin-add-externalLink');

	######## Ambassadors
	Route::match(['get','post'],'ambassadors','Admin\AdminController@ambassadors')->name('admin-ambassadors');

	Route::post('editAmbassador','Admin\AdminController@editAmbassador')->name('admin-edit-ambassador');
	Route::post('deleteAmbassador','Admin\AdminController@deleteAmbassador')->name('admin-delete-ambassador');
	Route::post('addAmbassador','Admin\AdminController@addAmbassador')->name('admin-add-ambassador');


	######## Offers
	Route::match(['get','post'],'offers','Admin\AdminController@offers')->name('admin-offers');
	Route::post('editOffer','Admin\AdminController@editOffer')->name('admin-edit-offer');
	Route::post('editBanner','Admin\AdminController@editBanner')->name('admin-edit-banner');
	Route::post('deleteOffer','Admin\AdminController@deleteOffer')->name('admin-delete-offer');
	Route::post('deleteBanner','Admin\AdminController@deleteBanner')->name('admin-delete-banner');
	Route::post('addOffer','Admin\AdminController@addOffer')->name('admin-add-offer');
	Route::post('addBanner','Admin\AdminController@addBanner')->name('admin-add-banner');


	######## Messages
	Route::match(['get','post'],'contactus','Admin\AdminController@contactus')->name('admin-contacts');
	Route::post('delete-message','Admin\AdminController@deleteContactus')->name('admin-delete-contactus');


	######## Guide Books
	Route::match(['get','post'],'guidebooks','Admin\AdminController@guidebooks')->name('admin-guidebooks');
	Route::post('editGuidebook','Admin\AdminController@editGuidebook')->name('admin-edit-guidebook');
	Route::post('deleteGuidebook','Admin\AdminController@deleteGuidebook')->name('admin-delete-guidebook');
	Route::post('addGuidebook','Admin\AdminController@addGuidebook')->name('admin-add-guidebook');



	
	Route::get('/','Admin\AdminController@login')->name('admin-login');
	Route::post('submit-login','Admin\AdminController@submitLogin')->name('admin-submit-login');
	

	Route::get('logout','Admin\AdminController@logout')->name('admin-logout');

});