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
// login and logout
Route::get('/login', 'Pages\LoginController@index')->name('loginpage');
Route::post('/dologin', 'Pages\LoginController@doLogin');
Route::get('/logout', function() {
  if( session()->has('hasLogin') )
  {
    session()->forget('hasLogin');
    session()->flush();
    return redirect( route('loginpage') );
  }
  else
  {
    return redirect( route('dashboardpage') );
  }
})->name('logoutpage');
//login and logout

// dahsboard
Route::get('/', function () { return redirect( route('dashboardpage') ); });
Route::get('/dashboard', 'Pages\DashboardController@index')->name('dashboardpage');
Route::get('/summary_ap', 'Pages\DashboardController@summary_ap');
Route::get('/location/{zone}', 'Pages\DevicesController@location');
Route::get('/selectedlocation', 'Pages\DevicesController@selectedlocation');
// dashboard

// users management
Route::group(['prefix' => '/users'], function() {
  Route::get('/', 'Pages\UsersController@index')->name('userpage');
  Route::get('/listuser', 'Pages\UsersController@listuser');
  Route::post('/add', 'Pages\UsersController@store');
  Route::put('/update/{id}', 'Pages\UsersController@update');
  Route::delete('/delete/{id}', 'Pages\UsersController@destroy');

  Route::get('/profile', 'Pages\UsersController@userprofile')->name('userprofile_page');
  Route::post('/saveprofile', 'Pages\UsersController@saveprofile');
  Route::post('/savepicture', 'Pages\UsersController@savepicture');
});
// users management

// mikrotik global user
Route::group(['prefix' => '/usermikrotik'], function() {
  Route::get('/', 'Pages\UserMikrotikController@index')->name('usermikrotikpage');
  Route::get('/listuser', 'Pages\UserMikrotikController@listuser');
  Route::post('/add', 'Pages\UserMikrotikController@store');
  Route::put('/update/{id}', 'Pages\UserMikrotikController@update');
  Route::delete('/delete/{id}', 'Pages\UserMikrotikController@destroy');
});
// mikrotik global user

// zone region and domain
Route::group(['prefix' => '/zone'], function() {
  Route::group(['prefix' => '/region'], function() {
    Route::get('/', 'Pages\RegionController@index')->name('zoneregionpage');
    Route::get('/listzoneregion', 'Pages\RegionController@listzoneregion');
    Route::post('/add', 'Pages\RegionController@store');
    Route::put('/update/{id}', 'Pages\RegionController@update');
    Route::delete('/delete/{id}', 'Pages\RegionController@destroy');
  });

  Route::group(['prefix' => 'domain'], function() {
    Route::get('/', 'Pages\RegionDomainController@index')->name('zonedomainpage');
    Route::get('/listzonedomain', 'Pages\RegionDomainController@listzonedomain');
    Route::post('/add', 'Pages\RegionDomainController@store');
    Route::put('/update/{id}', 'Pages\RegionDomainController@update');
    Route::delete('/delete/{id}', 'Pages\RegionDomainController@destroy');
  });
});
// zone region and domain

// devices
Route::group(['prefix' => 'devices'], function() {
  Route::get('/', 'Pages\DevicesController@index')->name('devicespage');
  Route::get('/listdevice', 'Pages\DevicesController@listdevice');
  Route::post('/add', 'Pages\DevicesController@store');
  Route::put('/update/{id}', 'Pages\DevicesController@update');
  Route::delete('/delete/{id}', 'Pages\DevicesController@destroy');
  Route::get('/monitor/{id}', 'Pages\DevicesController@detaildevice')->name('detaildevice');
  Route::get('/controller/{id}', 'Pages\DevicesController@controllerdevice');
});
// devices

// mikrotik
Route::get('/mikrotik/graphImage', 'Api\MikrotikAPI@showImageGraph');
// mikrotik
