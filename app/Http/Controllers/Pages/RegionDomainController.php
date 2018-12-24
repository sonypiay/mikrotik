<?php

namespace App\Http\Controllers\Pages;

use Illuminate\Http\Request;
use App\Database\Region;
use App\Database\RegionDomain;
use App\Database\Users;
use App\Http\Controllers\Controller;

class RegionDomainController extends Controller
{
  public function index( Request $request, Region $region, Users $users )
  {
    if( $request->session()->has('hasLogin') )
    {
      $zoneregion = new $region;
      $session = $request->session()->get('hasLogin');
      $users = $users->where( 'user_id', $session['userid'] )->first();

      return response()->view('pages.zonedomain', [
        'request' => $request,
        'getSession' => $session,
        'users' => $users,
        'zoneregion' => $zoneregion->orderBy('region_name', 'asc')->get()
      ]);
    }
    else
    {
      return redirect( route('loginpage') );
    }
  }

  public function listzonedomain( Request $request, RegionDomain $region )
  {
    $keywords = $request->keywords;
    $limit = 10;

    $region = new $region;
    if( empty( $keywords ) )
    {
      $region = $region->select('region_domain.id','region_domain.region_domain_name',
      'region_domain.region_domain_id','region.region_name','region.region_id')
      ->join('region', 'region_domain.region_id', '=', 'region.region_id')
      ->orderBy('region_domain.id', 'asc')->paginate( $limit );
    }
    else
    {
      $region = $region->select('region_domain.id','region_domain.region_domain_name',
      'region_domain.region_domain_id','region.region_name','region.region_id')
      ->join('region', 'region_domain.region_id', '=', 'region.region_id')
      ->where('region_domain_name', 'like', '%' . $keywords . '%')
      ->orWhere('region_domain_id', 'like', '%' . $keywords . '%')
      ->orderBy('region_domain.id', 'asc')->paginate( $limit );
    }

    return response()->json( $region, 200 );
  }

  public function store( Request $request, RegionDomain $region )
  {
    $domain_id = $request->domain_id;
    $domain_name = $request->domain_name;
    $regionid = $request->region;
    $region = new $region;
    if( $region->where('region_domain_id', '=', $domain_id)->count() === 1 )
    {
      $res = [
        'status' => 409,
        'statusText' => 'ID ' . $domain_id . ' already exists'
      ];
    }
    else
    {
      $region->region_domain_id = $domain_id;
      $region->region_domain_name = $domain_name;
      $region->region_id = $regionid;
      $region->save();
      $res = [
        'status' => 200,
        'statusText' => 'New zone domain created'
      ];
    }

    return response()->json($res, $res['status']);
  }

  public function update( Request $request, RegionDomain $region, $id )
  {
    $domain_id = $request->domain_id;
    $domain_name = $request->domain_name;
    $regionid = $request->region;
    $region = $region->where('id', '=', $id)->first();
    if( $domain_id == $region->region_domain_id )
    {
      $region->region_domain_id = $domain_id;
      $region->region_domain_name = $domain_name;
      $region->region_id = $regionid;
      $region->save();

      $res = [
        'status' => 200,
        'statusText' => 'Zone domain updated'
      ];
    }
    else
    {
      $checkregionid = $region->where('region_domain_id','=', $domain_id);
      if( $checkregionid->count() === 1 )
      {
        $res = [
          'status' => 409,
          'statusText' => 'ID ' . $domain_id . ' already exists'
        ];
      }
      else
      {
        $region->region_domain_id = $domain_id;
        $region->region_domain_name = $domain_name;
        $region->region_id = $regionid;
        $region->save();
        $res = [
          'status' => 200,
          'statusText' => 'Zone domain updated'
        ];
      }
    }

    return response()->json($res, $res['status']);
  }

  public function destroy( Request $request, RegionDomain $region, $id )
  {
    $region = $region->where('id', $id);
    if( $region->count() === 1 )
    {
      $region->delete();
      $res = [
        'status' => 200,
        'statusText' => 'Zone domain deleted'
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
