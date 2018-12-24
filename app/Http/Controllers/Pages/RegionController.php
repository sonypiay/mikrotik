<?php

namespace App\Http\Controllers\Pages;

use Illuminate\Http\Request;
use App\Database\Region;
use App\Database\Users;
use App\Http\Controllers\Controller;

class RegionController extends Controller
{
  public function index( Request $request, Users $users )
  {
    if( $request->session()->has('hasLogin') )
    {
      $session = $request->session()->get('hasLogin');
      $users = $users->where( 'user_id', $session['userid'] )->first();

      return response()->view('pages.zoneregion', [
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

  public function listzoneregion( Request $request, Region $region )
  {
    $keywords = $request->keywords;
    $limit = 10;

    $region = new $region;
    if( empty( $keywords ) )
    {
      $region = $region->orderBy('id', 'asc')->paginate( $limit );
    }
    else
    {
      $region = $region->where('region_name', 'like', '%' . $keywords . '%')
      ->orWhere('region_id', 'like', '%' . $keywords . '%')
      ->orderBy('id', 'asc')->paginate( $limit );
    }

    return response()->json( $region, 200 );
  }

  public function store( Request $request, Region $region )
  {
    $region_id = $request->region_id;
    $region_name = $request->region_name;
    $region = new $region;
    if( $region->where('region_id', '=', $region_id)->count() === 1 )
    {
      $res = [
        'status' => 409,
        'statusText' => 'ID ' . $region_id . ' already exists'
      ];
    }
    else
    {
      $region->region_id = $region_id;
      $region->region_name = $region_name;
      $region->save();
      $res = [
        'status' => 200,
        'statusText' => 'New zone region created'
      ];
    }

    return response()->json($res, $res['status']);
  }

  public function update( Request $request, Region $region, $id )
  {
    $region_id = $request->region_id;
    $region_name = $request->region_name;
    $region = $region->where('id', '=', $id)->first();
    if( $region_id == $region->region_id )
    {
      $region->region_id = $region_id;
      $region->region_name = $region_name;
      $region->save();

      $res = [
        'status' => 200,
        'statusText' => 'Zone region updated'
      ];
    }
    else
    {
      $checkregionid = $region->where('region_id','=', $region_id);
      if( $checkregionid->count() === 1 )
      {
        $res = [
          'status' => 409,
          'statusText' => 'ID ' . $region_id . ' already exists'
        ];
      }
      else
      {
        $region->region_id = $region_id;
        $region->region_name = $region_name;
        $region->save();
        $res = [
          'status' => 200,
          'statusText' => 'Zone region updated'
        ];
      }
    }

    return response()->json($res, $res['status']);
  }

  public function destroy( Request $request, Region $region, $id )
  {
    $region = $region->where('id', $id);
    if( $region->count() === 1 )
    {
      $region->delete();
      $res = [
        'status' => 200,
        'statusText' => 'Zone region deleted'
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
