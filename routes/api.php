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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => '/mikrotik'], function() {
  Route::get('/statusdevice/{id}', 'Api\MikrotikAPI@login');
  Route::get('/log/{id}', 'Api\MikrotikAPI@showlog');
  Route::get('/interface/{id}', 'Api\MikrotikAPI@showinterface');
  Route::get('/resource/{id}', 'Api\MikrotikAPI@device_info');
  Route::get('/ipaddress/{id}', 'Api\MikrotikAPI@ipaddress');
  Route::get('/vlan/{id}', 'Api\MikrotikAPI@showvlan');
  Route::get('/graph/{id}', 'Api\MikrotikAPI@showgraph');
  Route::get('/service/{id}', 'Api\MikrotikAPI@showservice');

  Route::group(['prefix' => 'add'], function() {
    Route::post('/vlan/{id}', 'Api\MikrotikAPI@addvlan');
    Route::post('/graph/{id}', 'Api\MikrotikAPI@addGraph');
  });
  Route::group(['prefix' => 'update'], function() {
    Route::put('/vlan/{id}', 'Api\MikrotikAPI@updatevlan');
  });
  Route::group(['prefix' => 'delete'], function() {
    Route::delete('/vlan/{id}', 'Api\MikrotikAPI@deletevlan');
    Route::delete('/graph/{id}', 'Api\MikrotikAPI@deleteGraph');
  });
  Route::group(['prefix' => 'find'], function() {
    Route::get('/service/{id}', 'Api\MikrotikAPI@findservice');
  });
});
