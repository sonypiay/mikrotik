<?php

namespace App\Mikrotik;

use RouterOS\RouterosAPI as Router;

class Mikrotik {

  var $ip;
  var $username;
  var $password;
  var $port = 8278;

  use MikrotikManipulate;

  public function __construct( $ip, $username, $password, $port )
  {
    $this->ip = $ip;
    $this->username = $username;
    $this->password = $password;
    $this->port = $port;
  }

  public function login()
  {
    $routeros = new Router;
    $routeros->debug = false;
    $routeros->port = $this->port;
    if( $routeros->connect( $this->ip, $this->username, $this->password ) )
    {
      $res = [
        'ip' => $this->ip,
        'status' => 200,
        'response' => 'connected',
        'command' => $routeros
      ];
    }
    else
    {
      $res = [
        'ip' => $this->ip,
        'status' => 200,
        'response' => 'disconnected'
      ];
    }
    return $res;
  }

  public function statusDevice()
  {
    $login = $this->login();
    if( $login['response'] == 'connected' )
    {
      $res = [
        'ip' => $this->ip,
        'status' => 200,
        'response' => 'Connected'
      ];
    }
    else
    {
      $res = [
        'ip' => $this->ip,
        'status' => 200,
        'response' => 'Not connected'
      ];
    }
    return $res;
  }

  public function showlog()
  {
    $login = $this->login();
    if( $login['response'] == 'connected' )
    {
      $res = [
        'ip' => $this->ip,
        'status' => 200,
        'response' => 'Connected',
        'result' => $login['command']->comm('/log/print')
      ];
    }
    else
    {
      $res = [
        'ip' => $this->ip,
        'status' => 200,
        'response' => 'Not connected',
        'result' => null
      ];
    }
    return $res;
  }

  public function interface()
  {
    $login = $this->login();
    if( $login['response'] == 'connected' )
    {
      $res = [
        'ip' => $this->ip,
        'status' => 200,
        'response' => 'Connected',
        'result' => $login['command']->comm('/interface/print')
      ];
    }
    else
    {
      $res = [
        'ip' => $this->ip,
        'status' => 200,
        'response' => 'Not connected',
        'result' => null
      ];
    }
    return $res;
  }

  public function resources() {
    $login = $this->login();
    if( $login['response'] == 'connected' )
    {
      $res = [
        'ip' => $this->ip,
        'status' => 200,
        'response' => 'Connected',
        'result' => $login['command']->comm('/system/resource/print')
      ];
    }
    else
    {
      $res = [
        'ip' => $this->ip,
        'status' => 200,
        'response' => 'Not connected',
        'result' => null
      ];
    }
    return $res;
  }

  public function ipaddress()
  {
    $login = $this->login();
    if( $login['response'] == 'connected' )
    {
      $res = [
        'ip' => $this->ip,
        'status' => 200,
        'response' => 'Connected',
        'result' => $login['command']->comm('/ip/address/print')
      ];
    }
    else
    {
      $res = [
        'ip' => $this->ip,
        'status' => 200,
        'response' => 'Not connected',
        'result' => null
      ];
    }
    return $res;
  }

  public function interface_vlan()
  {
    $login = $this->login();
    if( $login['response'] == 'connected' )
    {
      $res = [
        'ip' => $this->ip,
        'status' => 200,
        'response' => 'Connected',
        'result' => $login['command']->comm('/interface/vlan/print')
      ];
    }
    else
    {
      $res = [
        'ip' => $this->ip,
        'status' => 200,
        'response' => 'Not connected',
        'result' => null
      ];
    }
    return $res;
  }

  public function getGraphInterface()
  {
    $login = $this->login();
    if( $login['response'] == 'connected' )
    {
      $res = [
        'ip' => $this->ip,
        'status' => 200,
        'response' => 'Connected',
        'result' => $login['command']->comm('/tool/graphing/interface/print')
      ];
    }
    else
    {
      $res = [
        'ip' => $this->ip,
        'status' => 200,
        'response' => 'Not connected',
        'result' => null
      ];
    }
    return $res;
  }

  public function getServices()
  {
    $login = $this->login();
    if( $login['response'] == 'connected' )
    {
      $res = [
        'ip' => $this->ip,
        'status' => 200,
        'response' => 'Connected',
        'result' => $login['command']->comm('/ip/service/print')
      ];
    }
    else
    {
      $res = [
        'ip' => $this->ip,
        'status' => 200,
        'response' => 'Not connected',
        'result' => null
      ];
    }
    return $res;
  }

  public function findService( $request )
  {
    $name = $request->name;
    $value = $request->value;
    $login = $this->login();
    if( $login['response'] == 'connected' )
    {
      $res = [
        'ip' => $this->ip,
        'status' => 200,
        'response' => 'Connected',
        'result' => $login['command']->comm('/ip/service/print', [
          '?' . $name => $value
        ])
      ];
    }
    else
    {
      $res = [
        'ip' => $this->ip,
        'status' => 200,
        'response' => 'Not connected',
        'result' => null
      ];
    }
    return $res;
  }

  public function locationid()
  {
    $login = $this->login();
    if( $login['response'] == 'connected' )
    {
      $res = [
        'ip' => $this->ip,
        'status' => 200,
        'response' => 'Connected',
        'result' => $login['command']->comm('/ip/hotspot/profile/print', [
          '?name' => 'hsprof-BiznetHotspot'
        ])
      ];
    }
    else
    {
      $res = [
        'ip' => $this->ip,
        'status' => 200,
        'response' => 'Not connected',
        'result' => null
      ];
    }
    return $res;
  }

  public function hotspot_server()
  {
    $login = $this->login();
    if( $login['response'] == 'connected' )
    {
      $res = [
        'ip' => $this->ip,
        'status' => 200,
        'response' => 'Connected',
        'result' => $login['command']->comm('/ip/hotspot/print')
      ];
    }
    else
    {
      $res = [
        'ip' => $this->ip,
        'status' => 200,
        'response' => 'Not connected',
        'result' => null
      ];
    }
    return $res;
  }

  public function walled_garden()
  {
    $login = $this->login();
    if( $login['response'] == 'connected' )
    {
      $res = [
        'ip' => $this->ip,
        'status' => 200,
        'response' => 'Connected',
        'result' => $login['command']->comm('/ip/hotspot/walled-garden/print')
      ];
    }
    else
    {
      $res = [
        'ip' => $this->ip,
        'status' => 200,
        'response' => 'Not connected',
        'result' => null
      ];
    }
    return $res;
  }

  public function show_bandwidth()
  {
    $login = $this->login();
    if( $login['response'] == 'connected' )
    {
      $download = $login['command']->comm('/queue/type/print', [
        '?name' => 'pcq-down-biznethotspot'
      ]);

      $upload = $login['command']->comm('/queue/type/print', [
        '?name' => 'pcq-up-biznethotspot'
      ]);

      $res = [
        'ip' => $this->ip,
        'status' => 200,
        'response' => 'Connected',
        'result' => [
          'download' => $download,
          'upload' => $upload
        ]
      ];
    }
    else
    {
      $res = [
        'ip' => $this->ip,
        'status' => 200,
        'response' => 'Not connected',
        'result' => null
      ];
    }
    return $res;
  }

  public function sessionTimeout()
  {
    $login = $this->login();
    if( $login['response'] == 'connected' )
    {
      $res = [
        'ip' => $this->ip,
        'status' => 200,
        'response' => 'Connected',
        'result' => $login['command']->comm('/ip/hotspot/user/profile/print', [
          '?name' => 'BiznetHotspot'
        ])
      ];
    }
    else
    {
      $res = [
        'ip' => $this->ip,
        'status' => 200,
        'response' => 'Not connected',
        'result' => null
      ];
    }
    return $res;
  }
}

trait MikrotikManipulate {
  public function addVlan($request)
  {
    $vlanname = $request->vlanname;
    $vlanid = $request->vlanid;
    $arp = $request->arp;
    $iface = $request->iface;
    $disabled = $request->disabled == 'false' ? 'no' : 'yes';
    $login = $this->login();
    if( $login['response'] == 'connected' )
    {
      $comm = $login['command']->comm('/interface/vlan/add', [
        'name' => $vlanname,
        'vlan-id' => $vlanid,
        'arp' => $arp,
        'interface' => $iface,
        'disabled' => $disabled
      ]);

      if( isset( $comm['!trap'] ) )
      {
        $res = [
          'ip' => $this->ip,
          'status' => 409,
          'response' => $comm['!trap'][0]['message'],
        ];
      }
      else
      {
        $res = [
          'ip' => $this->ip,
          'status' => 200,
          'response' => 'New vlan added',
        ];
      }
    }
    else
    {
      $res = [
        'ip' => $this->ip,
        'status' => 200,
        'response' => 'Not connected',
      ];
    }
    return $res;
  }

  public function updateVlan($request)
  {
    $vlanname = $request->vlanname;
    $vlanid = $request->vlanid;
    $arp = $request->arp;
    $iface = $request->iface;
    $disabled = $request->disabled == 'false' ? 'no' : 'yes';
    $login = $this->login();
    if( $login['response'] == 'connected' )
    {
      $comm = $login['command']->comm('/interface/vlan/set', [
        '.id' => $request->id,
        'name' => $vlanname,
        'vlan-id' => $vlanid,
        'arp' => $arp,
        'interface' => $iface,
        'disabled' => $disabled
      ]);

      if( isset( $comm['!trap'] ) )
      {
        $res = [
          'ip' => $this->ip,
          'status' => 409,
          'response' => $comm['!trap'][0]['message']
        ];
      }
      else
      {
        $res = [
          'ip' => $this->ip,
          'status' => 200,
          'response' => 'Vlan has changes',
        ];
      }
    }
    else
    {
      $res = [
        'ip' => $this->ip,
        'status' => 200,
        'response' => 'Not connected',
      ];
    }
    return $res;
  }

  public function deleteVlan( $request )
  {
    $id = $request->id;
    $vlanname = $request->vlanname;
    $login = $this->login();
    if( $login['response'] == 'connected' )
    {
      $comm = $login['command']->comm('/interface/vlan/remove', [
        '.id' => $id
      ]);

      if( isset( $comm['!trap'] ) )
      {
        $res = [
          'ip' => $this->ip,
          'status' => 500,
          'response' => $comm['!trap'][0]['message']
        ];
      }
      else
      {
        $res = [
          'ip' => $this->ip,
          'status' => 200,
          'response' => $vlanname . ' has removed',
        ];
      }
    }
    else
    {
      $res = [
        'ip' => $this->ip,
        'status' => 200,
        'response' => 'Not connected',
      ];
    }
    return $res;
  }

  public function addGraph( $request )
  {
    $iface = $request->iface;
    $storeondisk = $request->storeondisk;
    $allow_address = $request->allow_address;

    $login = $this->login();
    if( $login['response'] == 'connected' )
    {
      $comm = $login['command']->comm('/tool/graphing/interface/add', [
        'allow-address' => $allow_address,
        'store-on-disk' => $storeondisk,
        'interface' => $iface
      ]);

      if( isset( $comm['!trap'] ) )
      {
        $res = [
          'ip' => $this->ip,
          'status' => 400,
          'response' => $comm['!trap'][0]['message'],
        ];
      }
      else
      {
        $res = [
          'ip' => $this->ip,
          'status' => 200,
          'response' => 'New interface added',
        ];
      }
    }
    else
    {
      $res = [
        'ip' => $this->ip,
        'status' => 200,
        'response' => 'Not connected',
      ];
    }
    return $res;
  }

  public function deleteGraph( $request )
  {
    $id = $request->id;
    $iface = $request->iface;
    $login = $this->login();
    if( $login['response'] == 'connected' )
    {
      $comm = $login['command']->comm('/tool/graphing/interface/remove', [
        '.id' => $id
      ]);

      if( isset( $comm['!trap'] ) )
      {
        $res = [
          'ip' => $this->ip,
          'status' => 500,
          'response' => $comm['!trap'][0]['message']
        ];
      }
      else
      {
        $res = [
          'ip' => $this->ip,
          'status' => 200,
          'response' => $iface . ' graph has removed',
        ];
      }
    }
    else
    {
      $res = [
        'ip' => $this->ip,
        'status' => 200,
        'response' => 'Not connected',
      ];
    }
    return $res;
  }

  public function updateLocation( $request )
  {
    $location_id = $request->location_id;
    $id = $request->id;

    $login = $this->login();
    if( $login['response'] == 'connected' )
    {
      $comm = $login['command']->comm('/ip/hotspot/profile/set', [
        '.id' => $id,
        'radius-location-id' => $location_id
      ]);

      if( isset( $comm['!trap'] ) )
      {
        $res = [
          'ip' => $this->ip,
          'status' => 500,
          'response' => $comm['!trap'][0]['message']
        ];
      }
      else
      {
        $res = [
          'ip' => $this->ip,
          'status' => 200,
          'response' => 'Location ID has changed',
        ];
      }
    }
    else
    {
      $res = [
        'ip' => $this->ip,
        'status' => 200,
        'response' => 'Not connected',
      ];
    }
    return $res;
  }

  public function updateRadiusIp( $request )
  {
    $radius_ip = $request->radius_ip;
    $id = $request->id;

    $login = $this->login();
    if( $login['response'] == 'connected' )
    {
      $comm = $login['command']->comm('/ip/hotspot/profile/set', [
        '.id' => $id,
        'hotspot-address' => $radius_ip
      ]);

      if( isset( $comm['!trap'] ) )
      {
        $res = [
          'ip' => $this->ip,
          'status' => 500,
          'response' => $comm['!trap'][0]['message']
        ];
      }
      else
      {
        $res = [
          'ip' => $this->ip,
          'status' => 200,
          'response' => 'Radius address has changed',
        ];
      }
    }
    else
    {
      $res = [
        'ip' => $this->ip,
        'status' => 200,
        'response' => 'Not connected',
      ];
    }
    return $res;
  }

  public function addWalledGarden( $request )
  {
    $dsthost = $request->dsthost;

    $login = $this->login();
    if( $login['response'] == 'connected' )
    {
      $comm = $login['command']->comm('/ip/hotspot/walled-garden/add', [
        'dst-host' => $dsthost
      ]);

      if( isset( $comm['!trap'] ) )
      {
        $res = [
          'ip' => $this->ip,
          'status' => 500,
          'response' => $comm['!trap'][0]['message']
        ];
      }
      else
      {
        $res = [
          'ip' => $this->ip,
          'status' => 200,
          'response' => 'New walled garden added',
        ];
      }
    }
    else
    {
      $res = [
        'ip' => $this->ip,
        'status' => 200,
        'response' => 'Not connected',
      ];
    }
    return $res;
  }

  public function updateWalledGarden( $request )
  {
    $dsthost = $request->dsthost;
    $id = $request->id;

    $login = $this->login();
    if( $login['response'] == 'connected' )
    {
      $comm = $login['command']->comm('/ip/hotspot/walled-garden/set', [
        '.id' => $id,
        'dst-host' => $dsthost
      ]);

      if( isset( $comm['!trap'] ) )
      {
        $res = [
          'ip' => $this->ip,
          'status' => 500,
          'response' => $comm['!trap'][0]['message']
        ];
      }
      else
      {
        $res = [
          'ip' => $this->ip,
          'status' => 200,
          'response' => 'Walled garden has been updated',
        ];
      }
    }
    else
    {
      $res = [
        'ip' => $this->ip,
        'status' => 200,
        'response' => 'Not connected',
      ];
    }
    return $res;
  }

  public function deleteWalledGarden( $request )
  {
    $id = $request->id;
    $login = $this->login();
    if( $login['response'] == 'connected' )
    {
      $comm = $login['command']->comm('/ip/hotspot/walled-garden/remove', [
        '.id' => $id
      ]);

      if( isset( $comm['!trap'] ) )
      {
        $res = [
          'ip' => $this->ip,
          'status' => 500,
          'response' => $comm['!trap'][0]['message']
        ];
      }
      else
      {
        $res = [
          'ip' => $this->ip,
          'status' => 200,
          'response' => 'Walled garden has deleted',
        ];
      }
    }
    else
    {
      $res = [
        'ip' => $this->ip,
        'status' => 200,
        'response' => 'Not connected',
      ];
    }
    return $res;
  }

  public function updateBandwidth( $request )
  {
    $upload = $request->upload;
    $download = $request->download;
    $id_upload = $request->id_upload;
    $id_download = $request->id_download;

    $login = $this->login();
    if( $login['response'] == 'connected' )
    {
      $download = $login['command']->comm('/queue/type/set', [
        '.id' => $id_download,
        'pcq-rate' => $download
      ]);

      $upload = $login['command']->comm('/queue/type/set', [
        '.id' => $id_upload,
        'pcq-rate' => $upload
      ]);

      $res = [
        'ip' => $this->ip,
        'status' => 200,
        'response' => 'Update changed'
      ];
    }
    else
    {
      $res = [
        'ip' => $this->ip,
        'status' => 200,
        'response' => 'Not connected',
        'result' => null
      ];
    }
    return $res;
  }

  public function updateSessionTimeout( $request )
  {
    $id = $request->id;
    $sessiontimeout = $request->sessiontimeout;
    $keepalivetimeout = $request->keepalivetimeout;

    $login = $this->login();
    if( $login['response'] == 'connected' )
    {
      $comm = $login['command']->comm('/ip/hotspot/user/profile/set', [
        '.id' => $id,
        'session-timeout' => $sessiontimeout,
        'keepalive-timeout' => $keepalivetimeout
      ]);

      if( isset( $comm['!trap'] ) )
      {
        $res = [
          'ip' => $this->ip,
          'status' => 500,
          'response' => $comm['!trap'][0]['message']
        ];
      }
      else
      {
        $res = [
          'ip' => $this->ip,
          'status' => 200,
          'response' => 'Session timeout updated',
        ];
      }
    }
    else
    {
      $res = [
        'ip' => $this->ip,
        'status' => 200,
        'response' => 'Not connected',
      ];
    }
    return $res;
  }
}
