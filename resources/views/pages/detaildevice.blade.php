@extends('master')
@section('headtitle', $device->device_name)
@section('maincontent')
<getdevices url="{{ url('/') }}" :device="{{ json_encode( $device ) }}"></getdevices>
@endsection
