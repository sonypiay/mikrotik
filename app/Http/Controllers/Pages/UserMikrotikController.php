<?php

namespace App\Http\Controllers\Pages;

use Illuminate\Http\Request;
use App\Database\UserMikrotik;
use App\Http\Controllers\Controller;

class UserMikrotikController extends Controller
{
  public function index( Request $request )
  {
    if( $request->session()->has('hasLogin') )
    {
      return response()->view('pages.usermikrotik', [
        'request' => $request,
        'getSession' => $request->session()
      ]);
    }
    else
    {
      return redirect( route('loginpage') );
    }
  }

  public function listuser( Request $request, UserMikrotik $usermikrotik )
  {
    $searchuser = $request->searchuser;
    $limit = 10;
    $query = new $usermikrotik;
    if( empty( $searchuser ) )
    {
      $query = $query->orderBy('id','desc')->paginate( $limit );
    }
    else
    {
      $query = $query->where('username','like','%' . $searchuser . '%')
      ->orderBy('id','desc')
      ->paginate( $limit );
    }

    return response()->json( $query, 200 );
  }

  public function store( Request $request, UserMikrotik $usermikrotik )
  {
    $username = $request->username;
    $password = $request->password;
    $port = $request->port;

    $usermikrotik = new $usermikrotik;
    $usermikrotik->username_mikrotik = $username;
    $usermikrotik->password_mikrotik = $password;
    $usermikrotik->port = $port;
    $usermikrotik->save();
    $res = [
      'status' => 200,
      'statusText' => 'New usermikrotik created.'
    ];

    return response()->json($res, $res['status']);
  }

  public function update( Request $request, UserMikrotik $usermikrotik, $id )
  {
    $username = $request->username;
    $password = $request->password;
    $port = $request->port;

    $usermikrotik = $usermikrotik->where('id',$id)->first();
    $usermikrotik->username_mikrotik = $username;
    $usermikrotik->password_mikrotik = $password;
    $usermikrotik->port = $port;
    $usermikrotik->save();
    $res = [
      'status' => 200,
      'statusText' => 'Update success'
    ];

    return response()->json($res, $res['status']);
  }

  public function destroy( UserMikrotik $usermikrotik, $id )
  {
    $query = $usermikrotik->where('id', $id);
    if( $query->count() === 1 )
    {
      $query->delete();
      $res = [
        'status' => 200,
        'statusText' => 'User mikrotik deleted'
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
}
