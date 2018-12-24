@extends('master')
@section('headtitle', $device->device_name . ' | ' . $device->device_ip)
@section('maincontent')
<getdevices url="{{ url('/') }}" :device="{{ json_encode( $device ) }}" />
@endsection
