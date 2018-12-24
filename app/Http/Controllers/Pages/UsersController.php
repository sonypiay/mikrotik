<?php

namespace App\Http\Controllers\Pages;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Database\Region;
use App\Database\RegionDomain;
use App\Database\Users;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
  public function index( Request $request, Users $users )
  {
    if( $request->session()->has('hasLogin') )
    {
      $session = $request->session()->get('hasLogin');
      $users = $users->where( 'user_id', $session['userid'] )->first();

      return response()->view('pages.users', [
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

  public function listuser( Request $request, Users $users )
  {
    $searchuser = $request->searchuser;
    $limit = 10;
    $query = new $users;
    if( empty( $searchuser ) )
    {
      $query = $query->orderBy('user_id', 'desc')
      ->paginate( $limit );
    }
    else
    {
      $query = $query->where('fullname', 'like', '%' . $searchuser . '%')
      ->orWhere('username', 'like', '%' .  $searchuser . '%')
      ->orderBy('user_id', 'desc')
      ->paginate( $limit );
    }
    return response()->json( $query, 200 );
  }

  public function store( Request $request, Users $users )
  {
    $fullname = $request->fullname;
    $username = $request->username;
    $password = $request->password;
    $privilege = $request->privilege;
    $users = new $users;
    if( $users->where('username','=',$username)->count() === 1 )
    {
      $res = [
        'status' => 409,
        'statusText' => 'Username has already exists'
      ];
    }
    else
    {
      $users->fullname = $fullname;
      $users->username = $username;
      $users->password = Hash::make( $password );
      $users->privilege = $privilege;
      $users->save();
      $res = [
        'status' => 200,
        'statusText' => 'New user created.'
      ];
    }
    return response()->json( $res, $res['status'] );
  }

  public function update( Request $request, Users $users, $id )
  {
    $fullname = $request->fullname;
    $username = $request->username;
    $password = $request->password;
    $privilege = $request->privilege;
    $selecteduser = $users->where('user_id','=', $id)->first();
    if( $username == $selecteduser->username )
    {
      $updated = $selecteduser;
      $updated->fullname = $fullname;
      $updated->username = $username;
      if( ! empty( $password ) ) { $updated->password = Hash::make( $password ); }
      $updated->privilege = $privilege;
      $updated->save();
      $res = [
        'status' => 200,
        'statusText' => 'User updated.'
      ];
    }
    else
    {
      $check = $users->where('username', '=', $username);
      if( $check->count() === 1 )
      {
        $res = [
          'status' => 409,
          'statusText' => 'Username has already exists'
        ];
      }
      else
      {
        $updated = $users->where('user_id','=', $id)->first();
        $updated->fullname = $fullname;
        $updated->username = $username;
        if( ! empty( $password ) ) { $updated->password = Hash::make( $password ); }
        $updated->privilege = $privilege;
        $updated->save();
        $res = [
          'status' => 200,
          'statusText' => 'User updated.'
        ];
      }
    }
    return response()->json( $res, $res['status'] );
  }

  public function destroy( Request $request, Users $users, $id )
  {
    $users = $users->where('user_id', $id);
    if( $users->count() === 1 )
    {
      $users->delete();
      $res = [
        'status' => 200,
        'statusText' => 'User updated.'
      ];
    }
    else
    {
      $res = [
        'status' => 204,
        'statusText' => ''
      ];
    }
    return response()->json( $res, $res['status'] );
  }
}
