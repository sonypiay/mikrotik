@extends('master')
@section('headtitle','Mikrotik Controller | Devices')
@section('maincontent')
<devices url="{{ url('/') }}" :region="{{ json_encode( $region ) }}"></devices>
@endsection
