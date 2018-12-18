@extends('master')
@section('headtitle','Mikrotik Controller | Devices')
@section('maincontent')
<devices url="{{ url('/') }}"
v-bind:usermikrotik="{{ json_encode( $usermikrotik ) }}"
v-bind:region="{{ json_encode( $region ) }}"></devices>
@endsection
