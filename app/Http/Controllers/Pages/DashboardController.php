<?php

namespace App\Http\Controllers\Pages;

use Illuminate\Http\Request;
use App\Database\Users;
use App\Database\Region;
use App\Database\RegionDomain;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
  public function index( Request $request, Users $users )
  {
    if( $request->session()->has('hasLogin') )
    {
      return response()->view('pages.dashboard', [
        'request' => $request,
        'getSession' => $request->session()
      ]);
    }
    else
    {
      return redirect( route('loginpage') );
    }
  }
}
