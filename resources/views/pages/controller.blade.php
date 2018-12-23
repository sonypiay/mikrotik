@extends('master')
@section('headtitle', $device->device_name)
@section('maincontent')
<controllerdevice url="{{ url('/') }}" :device="{{ json_encode( $device ) }}" />
@endsection
