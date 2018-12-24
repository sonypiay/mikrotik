<?php

namespace App\Http\Controllers\Pages;

use Illuminate\Http\Request;
use App\Database\Users;
use App\Database\RegionDomain;
use App\Database\Devices;
use App\Database\UserMikrotik;
use App\Http\Controllers\Api\MikrotikAPI;
use App\Http\Controllers\Controller;

class DevicesController extends Controller
{
  public function index( Request $request, RegionDomain $region, Users $users )
  {
    if( $request->session()->has('hasLogin') )
    {
      $region = new $region;
      $session = $request->session()->get('hasLogin');
      $users = $users->where( 'user_id', $session['userid'] )->first();

      return response()->view('pages.devices', [
        'request' => $request,
        'getSession' => $session,
        'users' => $users,
        'region' => $region->orderBy('region_domain_name', 'asc')->get()
      ]);
    }
    else
    {
      return redirect( route('loginpage') );
    }
  }

  public function detaildevice( Request $request, Devices $device, Users $users, $id )
  {
    if( $request->session()->has('hasLogin') )
    {
      $device = $device->join('region_domain', 'devices.region_domain_id', '=', 'region_domain.region_domain_id')
      ->join('region', 'region_domain.region_id', '=', 'region.region_id')
      ->where('devices.device_id', $id)->first();
      if( $device->count() == 0 ) abort(404);

      $session = $request->session()->get('hasLogin');
      $users = $users->where( 'user_id', $session['userid'] )->first();

      return response()->view('pages.detaildevice', [
        'request' => $request,
        'getSession' => $session,
        'users' => $users,
        'device' => $device
      ]);
    }
    else
    {
      return redirect( route('loginpage') );
    }
  }

  public function listdevice( Request $request, Devices $devices )
  {
    $keywords = $request->keywords;
    $limit = $request->limit;
    $zone = $request->zone;
    $query = new $devices;
    if( empty( $keywords ) )
    {
      if( $zone == 'all' )
      {
        $query = $devices->join('region_domain', 'devices.region_domain_id', '=', 'region_domain.region_domain_id')
        ->orderBy('devices.device_id', 'desc')
        ->paginate( $limit );
      }
      else
      {
        $query = $devices->join('region_domain', 'devices.region_domain_id', '=', 'region_domain.region_domain_id')
        ->where('devices.region_domain_id', '=', $zone)
        ->orderBy('devices.device_id', 'desc')
        ->paginate( $limit );
      }
    }
    else
    {
      if( $zone == 'all' )
      {
        $query = $devices->where('device_name', 'like', '%' . $keywords . '%')
        ->orWhere('device_ip', 'like', '%' . $keywords . '%')
        ->orWhere('device_type', 'like', '%' . $keywords . '%')
        ->join('region_domain', 'devices.region_domain_id', '=', 'region_domain.region_domain_id')
        ->orderBy('devices.device_id', 'desc')
        ->paginate( $limit );
      }
      else
      {
        $query = $devices->where([
          ['device_name', 'like', '%' . $keywords . '%'],
          ['devices.region_domain_id', '=', $zone]
        ])
        ->orWhere([
          ['device_ip', 'like', '%' . $keywords . '%'],
          ['devices.region_domain_id', '=', $zone]
        ])
        ->orWhere([
          ['device_type', 'like', '%' . $keywords . '%'],
          ['devices.region_domain_id', '=', $zone]
        ])
        ->join('region_domain', 'devices.region_domain_id', '=', 'region_domain.region_domain_id')
        ->orderBy('devices.device_id', 'desc')
        ->paginate( $limit );
      }
    }

    $data = [];
    $data['result'] = $query;
    foreach( $query as $key => $value )
    {
      //$router = new MikrotikAPI;
      //$data['statusdevice'][] = $router->login( $value->device_id );
      $data['statusdevice'][] = null;
    }
    return response()->json( $data );
  }

  public function store( Request $request, Devices $devices )
  {
    $device_ip = $request->device_ip;
    $device_name = $request->device_name;
    $device_type = $request->device_type;
    $device_desc = $request->device_desc;
    $region = $request->region;
    $username_mikrotik = $request->username_mikrotik;
    $password_mikrotik = $request->password_mikrotik;
    $portapi = $request->portapi;
    $query = new $devices;
    if( $query->where('device_ip', '=', $device_ip)->count() === 1 )
    {
      $res = [
        'status' => 409,
        'statusText' => 'IP ' . $device_ip . ' already exists'
      ];
    }
    else
    {
      $query->device_name = $device_name;
      $query->device_type = $device_type;
      $query->device_ip = $device_ip;
      $query->region_domain_id = $region;
      $query->device_username = $username_mikrotik;
      $query->device_password = $password_mikrotik;
      $query->device_port_api = $portapi;
      $query->save();
      $res = [
        'status' => 200,
        'statusText' => 'New devices created'
      ];
    }
    return response()->json($res, $res['status']);
  }

  public function update( Request $request, Devices $devices, $id )
  {
    $device_ip = $request->device_ip;
    $device_name = $request->device_name;
    $device_type = $request->device_type;
    $device_desc = $request->device_desc;
    $region = $request->region;
    $username_mikrotik = $request->username_mikrotik;
    $password_mikrotik = $request->password_mikrotik;
    $portapi = $request->portapi;
    $getdevice = $devices->where('device_id', $id)->first();
    if( $device_ip == $getdevice->device_ip )
    {
      $getdevice->device_name = $device_name;
      $getdevice->device_type = $device_type;
      $getdevice->device_description = $device_desc;
      $getdevice->device_ip = $device_ip;
      $getdevice->region_domain_id = $region;
      $getdevice->device_username = $username_mikrotik;
      $getdevice->device_password = $password_mikrotik;
      $getdevice->device_port_api = $portapi;
      $getdevice->save();
      $res = [
        'status' => 200,
        'statusText' => 'Devices updated'
      ];
    }
    else
    {
      $checkip = $devices->where('device_ip','=',$device_ip);
      if( $checkip->count() === 1 )
      {
        $res = [
          'status' => 409,
          'statusText' => 'IP ' . $device_ip . ' already exists'
        ];
      }
      else
      {
        $getdevice->device_name = $device_name;
        $getdevice->device_type = $device_type;
        $getdevice->device_description = $device_desc;
        $getdevice->device_ip = $device_ip;
        $getdevice->region_domain_id = $region;
        $getdevice->device_username = $username_mikrotik;
        $getdevice->device_password = $password_mikrotik;
        $getdevice->device_port_api = $portapi;
        $getdevice->save();
        $res = [
          'status' => 200,
          'statusText' => 'Devices updated'
        ];
      }
    }
    return response()->json($res, $res['status']);
  }

  public function destroy( Devices $devices, $id )
  {
    $query = $devices->where('device_id', $id);
    if( $query->count() === 1 )
    {
      $query->delete();
      $res = [
        'status' => 200,
        'statusText' => 'Devices deleted'
      ];
    }
    else
    {
      $res = [
        'status' => 204,
        'statusText' => ''
      ];
    }
    return response()->json($res, $res['status']);
  }

  public function location( Request $request, RegionDomain $domain, Users $users, $zone )
  {
    $getdomain = $domain->where('region_domain_id', $zone)->first();
    if( $request->session()->has('hasLogin') )
    {
      $session = $request->session()->get('hasLogin');
      $users = $users->where( 'user_id', $session['userid'] )->first();

      return response()->view('pages.location', [
        'request' => $request,
        'getSession' => $session,
        'users' => $users,
        'domain' => $getdomain
      ]);
    }
    else
    {
      return redirect( route('loginpage') );
    }
  }

  public function selectedlocation( Request $request, Devices $devices )
  {
    $keywords = $request->keywords;
    $limit = $request->limit;
    $zone = $request->zone;
    $query = new $devices;

    if( empty( $keywords ) )
    {
      $query = $devices->join('region_domain', 'devices.region_domain_id', '=', 'region_domain.region_domain_id')
      ->where('devices.region_domain_id', '=', $zone)
      ->orderBy('devices.device_id', 'desc')
      ->paginate( $limit );
    }
    else
    {
      $query = $devices->where([
        ['device_name', 'like', '%' . $keywords . '%'],
        ['devices.region_domain_id', '=', $zone]
      ])
      ->orWhere([
        ['device_ip', 'like', '%' . $keywords . '%'],
        ['devices.region_domain_id', '=', $zone]
      ])
      ->orWhere([
        ['device_type', 'like', '%' . $keywords . '%'],
        ['devices.region_domain_id', '=', $zone]
      ])
      ->join('region_domain', 'devices.region_domain_id', '=', 'region_domain.region_domain_id')
      ->orderBy('devices.device_id', 'desc')
      ->paginate( $limit );
    }

    $data = [];
    $data['result'] = $query;
    foreach( $query as $key => $value )
    {
      $router = new MikrotikAPI;
      $data['statusdevice'][] = $router->login( $value->device_id );
      //$data['statusdevice'][] = null;
    }
    return response()->json( $data );
  }

  public function controllerdevice( Request $request, Devices $devices, Users $users, $id )
  {
    if( $request->session()->has('hasLogin') )
    {
      $device = $devices->where('devices.device_id', $id)->first();
      if( $device->count() == 0 ) abort(404);

      $session = $request->session()->get('hasLogin');
      $users = $users->where( 'user_id', $session['userid'] )->first();

      return response()->view('pages.controller', [
        'request' => $request,
        'getSession' => $session,
        'users' => $users,
        'device' => $device
      ]);
    }
    else
    {
      return redirect( route('loginpage') );
    }
  }
}
