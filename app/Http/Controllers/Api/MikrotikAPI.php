<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Mikrotik\Mikrotik;
use App\Database\Devices;
use App\Http\Controllers\Controller;

class MikrotikAPI extends Controller
{
  public function login( $id )
  {
    $devices = Devices::where('device_id', $id)->first();
    $mikrotik = new Mikrotik( $devices->device_ip, $devices->device_username, $devices->device_password, $devices->device_port_api );
    $login = $mikrotik->statusDevice();
    return $login;
  }

  public function showlog( $id )
  {
    $devices = Devices::where('device_id', $id)->first();
    $mikrotik = new Mikrotik( $devices->device_ip, $devices->device_username, $devices->device_password, $devices->device_port_api );
    $log = $mikrotik->showlog();
    return response()->json( $log );
  }

  public function showinterface( $id )
  {
    $devices = Devices::where('device_id', $id)->first();
    $mikrotik = new Mikrotik( $devices->device_ip, $devices->device_username, $devices->device_password, $devices->device_port_api );
    $interface = $mikrotik->interface();
    return response()->json( $interface );
  }

  public function device_info( $id )
  {
    $devices = Devices::where('device_id', $id)->first();
    $mikrotik = new Mikrotik( $devices->device_ip, $devices->device_username, $devices->device_password, $devices->device_port_api );
    $resources = $mikrotik->resources();
    $statusdevice = $mikrotik->statusDevice();
    $data = [
      'result' => [
        'resource' => $resources,
        'statusDevice' => $statusdevice
      ]
    ];
    return response()->json( $data, 200 );
  }

  public function ipaddress( $id )
  {
    $devices = Devices::where('device_id', $id)->first();
    $mikrotik = new Mikrotik( $devices->device_ip, $devices->device_username, $devices->device_password, $devices->device_port_api );
    $ip = $mikrotik->ipaddress();
    $data = [
      'data' => [
        'ipaddress' => $ip
      ]
    ];
    return response()->json( $data, 200 );
  }

  public function showvlan( $id )
  {
    $devices = Devices::where('device_id', $id)->first();
    $mikrotik = new Mikrotik( $devices->device_ip, $devices->device_username, $devices->device_password, $devices->device_port_api );
    $vlan = $mikrotik->interface_vlan();
    return response()->json( $vlan );
  }

  public function addvlan( Request $request, $id )
  {
    $devices = Devices::where('device_id', $id)->first();
    $mikrotik = new Mikrotik( $devices->device_ip, $devices->device_username, $devices->device_password, $devices->device_port_api );
    $vlan = $mikrotik->addVlan( $request );
    return response()->json( $vlan, $vlan['status'] );
  }

  public function updatevlan( Request $request, $id )
  {
    $devices = Devices::where('device_id', $id)->first();
    $mikrotik = new Mikrotik( $devices->device_ip, $devices->device_username, $devices->device_password, $devices->device_port_api );
    $vlan = $mikrotik->updateVlan( $request );
    return response()->json( $vlan, $vlan['status'] );
  }

  public function deletevlan( Request $request, $id )
  {
    $devices = Devices::where('device_id', $id)->first();
    $mikrotik = new Mikrotik( $devices->device_ip, $devices->device_username, $devices->device_password, $devices->device_port_api );
    $vlan = $mikrotik->deleteVlan( $request );
    return response()->json( $vlan, $vlan['status'] );
  }
}
