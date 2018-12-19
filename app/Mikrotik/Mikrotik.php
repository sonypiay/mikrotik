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
}
