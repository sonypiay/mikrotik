<?php

namespace App\Http\Controllers\Pages;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Database\Users;
use App\Database\RegionDomain;
use App\Database\Devices;
use App\Http\Controllers\Api\MikrotikAPI;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
  public function index( Request $request, Users $users )
  {
    if( $request->session()->has('hasLogin') )
    {
      $session = $request->session()->get('hasLogin');
      $users = $users->where( 'user_id', $session['userid'] )->first();
      
      return response()->view('pages.dashboard', [
        'request' => $request,
        'getSession' => $session,
        'users' => $users
      ]);
    }
    else
    {
      return redirect( route('loginpage') );
    }
  }

  public function summary_ap( Request $request, RegionDomain $domain, Devices $devices )
  {
    $get_totalap = $devices->select(
      DB::raw('count(*) as total_ap'),
      'region_domain.region_domain_id as domain_id',
      'region_domain.region_domain_name as domain_name'
    )
    ->join('region_domain', 'devices.region_domain_id', '=', 'region_domain.region_domain_id')
    ->orderBy('region_domain.region_domain_name')
    ->groupBy('devices.region_domain_id')
    ->get();
    $data = [];
    $data['statusdevice']['connect'] = [];
    $data['statusdevice']['disconnect'] = [];
    $data['result'] = [];
    $connect = 0;
    $disconnect = 0;
    foreach( $get_totalap as $value )
    {
      $devices = new $devices;
      $device = $devices->where('region_domain_id', $value->domain_id)->get();
      foreach( $device as $d )
      {
        $router = new MikrotikAPI;
        //disconnect

        if( $router->login( $d->device_id )['response'] !== 'Connected' )
        {
          $data['statusdevice']['disconnect'][$value->domain_id][] = [
            'domain_name' => $value->domain_id,
            'status' => $router->login( $d->device_id )['response']
          ];
        }

        //connected
        if( $router->login( $d->device_id )['response'] === 'Connected' )
        {
          $data['statusdevice']['connect'][$value->domain_id][] = [
            'domain_name' => $value->domain_id,
            'status' => $router->login( $d->device_id )['response']
          ];
        }
      }

      if( isset( $data['statusdevice']['connect'][$value->domain_id] ) )
      {
        $connect = count( $data['statusdevice']['connect'][$value->domain_id] );
      }

      if( isset( $data['statusdevice']['disconnect'][$value->domain_id] ) )
      {
        $disconnect = count( $data['statusdevice']['disconnect'][$value->domain_id] );
      }

      $data['result'][] = [
        'domain_id' => $value->domain_id,
        'domain_name' => $value->domain_name,
        'connect' => $connect,
        'disconnect' => $disconnect,
        'total' => $connect + $disconnect
      ];
    }

    return response()->json([
      'total' => count( $get_totalap ),
      'results' => $data['result']
    ]);
  }
}
