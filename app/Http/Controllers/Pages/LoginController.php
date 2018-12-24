<?php

namespace App\Http\Controllers\Pages;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Database\Users;

class LoginController extends Controller
{
  public function index( Request $request, Users $users )
  {
    if( $request->session()->has('hasLogin') )
    {
      return redirect( route('dashboardpage') );
    }
    else
    {
      return response()->view('login', [
        'request' => $request,
        'getSession' => $request->session()->all()
      ]);
    }
  }

  public function dologin( Request $request, Users $users )
  {
    $username = $request->username;
    $password = $request->password;

    $query = $users->where('username','=',$username);
    if( $query->count() === 1 )
    {
      $fetch = $query->first();
      if( Hash::check( $password, $fetch->password ) )
      {
        $res = [
          'status' => 200,
          'statusText' => 'Login success'
        ];
        $request->session()->put('hasLogin', [
          'userid' => $fetch->user_id,
          'privilege' => $fetch->privilege,
          'ip' => $request->server('REMOTE_ADDR'),
          'agent' => $request->server('HTTP_USER_AGENT'),
          'logintime' => time()
        ]);
      }
      else
      {
        $res = [
          'status' => 401,
          'statusText' => 'Invalid Password.'
        ];
      }
    }
    else
    {
      $res = [
        'status' => 401,
        'statusText' => 'Invalid username or password'
      ];
    }
    return response()->json($res, $res['status']);
  }
}
