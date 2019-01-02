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

  public function showgraph( $id )
  {
    $devices = Devices::where('device_id', $id)->first();
    $mikrotik = new Mikrotik( $devices->device_ip, $devices->device_username, $devices->device_password, $devices->device_port_api );
    $graph = $mikrotik->getGraphInterface();
    return response()->json( $graph, 200 );
  }

  public function showImageGraph( Request $request )
  {
    $ip = $request->ip;
    $port = $request->port === 80 ? 80 : $request->port;
    $filtertime = $request->filtertime;
    $iface = $request->iface;
    $url = 'http://' . $ip;
    if( $port === 80 )
    {
      $url .= '/graphs/iface/' . $iface . '/' . $filtertime . '.gif';
    }
    else
    {
      $url .= ':' . $port . '/graphs/iface/' . $iface . '/' . $filtertime . '.gif';
    }

    $ch = @curl_init();
    @curl_setopt_array($ch, [
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_URL => $url,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_USERAGENT => $request->server('HTTP_USER_AGENT'),
		  CURLOPT_HEADER => 0,
		  CURLOPT_VERBOSE => 0
	  ]);
    @curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
    @curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
    $res = @curl_exec( $ch ); @curl_close( $ch );
	  return response( $res )->header('Content-Type', 'image/gif');
  }

  public function addGraph( Request $request, $id )
  {
    $devices = Devices::where('device_id', $id)->first();
    $mikrotik = new Mikrotik( $devices->device_ip, $devices->device_username, $devices->device_password, $devices->device_port_api );
    $graph = $mikrotik->addGraph( $request );
    return response()->json( $graph, $graph['status'] );
  }

  public function deleteGraph( Request $request, $id )
  {
    $devices = Devices::where('device_id', $id)->first();
    $mikrotik = new Mikrotik( $devices->device_ip, $devices->device_username, $devices->device_password, $devices->device_port_api );
    $graph = $mikrotik->deleteGraph( $request );
    return response()->json( $graph, $graph['status'] );
  }

  public function showservice( $id )
  {
    $devices = Devices::where('device_id', $id)->first();
    $mikrotik = new Mikrotik( $devices->device_ip, $devices->device_username, $devices->device_password, $devices->device_port_api );
    $service = $mikrotik->showservice();
    return response()->json( $service );
  }
  public function findservice( Request $request, $id )
  {
    $devices = Devices::where('device_id', $id)->first();
    $mikrotik = new Mikrotik( $devices->device_ip, $devices->device_username, $devices->device_password, $devices->device_port_api );
    $service = $mikrotik->findService( $request );
    return response()->json( $service );
  }
  public function show_locationid( $id )
  {
    $devices = Devices::where('device_id', $id)->first();
    $mikrotik = new Mikrotik( $devices->device_ip, $devices->device_username, $devices->device_password, $devices->device_port_api );
    $location = $mikrotik->locationid();
    return response()->json( $location );
  }

  public function updateLocation( Request $request, $id )
  {
    $devices = Devices::where('device_id', $id)->first();
    $mikrotik = new Mikrotik( $devices->device_ip, $devices->device_username, $devices->device_password, $devices->device_port_api );
    $location = $mikrotik->updateLocation( $request );
    return response()->json( $location );
  }

  public function updateRadiusIp( Request $request, $id )
  {
    $devices = Devices::where('device_id', $id)->first();
    $mikrotik = new Mikrotik( $devices->device_ip, $devices->device_username, $devices->device_password, $devices->device_port_api );
    $location = $mikrotik->updateRadiusIp( $request );
    return response()->json( $location );
  }

  public function hotspot_server( $id )
  {
    $devices = Devices::where('device_id', $id)->first();
    $mikrotik = new Mikrotik( $devices->device_ip, $devices->device_username, $devices->device_password, $devices->device_port_api );
    $server = $mikrotik->hotspot_server();
    return response()->json( $server );
  }

  public function show_walledgarden( $id )
  {
    $devices = Devices::where('device_id', $id)->first();
    $mikrotik = new Mikrotik( $devices->device_ip, $devices->device_username, $devices->device_password, $devices->device_port_api );
    $walledgarden = $mikrotik->walled_garden();
    return response()->json( $walledgarden );
  }

  public function addWalledGarden( Request $request, $id )
  {
    $devices = Devices::where('device_id', $id)->first();
    $mikrotik = new Mikrotik( $devices->device_ip, $devices->device_username, $devices->device_password, $devices->device_port_api );
    $walledgarden = $mikrotik->addWalledGarden( $request );
    return response()->json( $walledgarden );
  }

  public function updateWalledGarden( Request $request, $id )
  {
    $devices = Devices::where('device_id', $id)->first();
    $mikrotik = new Mikrotik( $devices->device_ip, $devices->device_username, $devices->device_password, $devices->device_port_api );
    $walledgarden = $mikrotik->updateWalledGarden( $request );
    return response()->json( $walledgarden );
  }

  public function deleteWalledGarden( Request $request, $id )
  {
    $devices = Devices::where('device_id', $id)->first();
    $mikrotik = new Mikrotik( $devices->device_ip, $devices->device_username, $devices->device_password, $devices->device_port_api );
    $walledgarden = $mikrotik->deleteWalledGarden( $request );
    return response()->json( $walledgarden );
  }

  public function show_bandwidth( $id )
  {
    $devices = Devices::where('device_id', $id)->first();
    $mikrotik = new Mikrotik( $devices->device_ip, $devices->device_username, $devices->device_password, $devices->device_port_api );
    $bandwidth = $mikrotik->show_bandwidth();
    return response()->json( $bandwidth );
  }

  public function updateBandwidth( Request $request, $id )
  {
    $devices = Devices::where('device_id', $id)->first();
    $mikrotik = new Mikrotik( $devices->device_ip, $devices->device_username, $devices->device_password, $devices->device_port_api );
    $bandwidth = $mikrotik->updateBandwidth( $request );
    return response()->json( $bandwidth );
  }

  public function sessiontimeout( $id )
  {
    $devices = Devices::where('device_id', $id)->first();
    $mikrotik = new Mikrotik( $devices->device_ip, $devices->device_username, $devices->device_password, $devices->device_port_api );
    $sessiontimeout = $mikrotik->sessiontimeout();
    return response()->json( $sessiontimeout );
  }

  public function updateSessionTimeout( Request $request, $id )
  {
    $devices = Devices::where('device_id', $id)->first();
    $mikrotik = new Mikrotik( $devices->device_ip, $devices->device_username, $devices->device_password, $devices->device_port_api );
    $sessiontimeout = $mikrotik->updateSessionTimeout( $request );
    return response()->json( $sessiontimeout );
  }
}
