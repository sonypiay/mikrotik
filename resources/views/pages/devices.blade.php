@extends('master')
@section('headtitle','Mikrotik Controller | Devices')
@section('maincontent')
<devices url="{{ url('/') }}"
:usermikrotik="{{ json_encode( $usermikrotik ) }}"
:region="{{ json_encode( $region ) }}"></devices>
@endsection
