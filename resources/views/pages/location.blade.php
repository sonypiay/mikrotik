@extends('master')
@section('headtitle','Location | ' . $domain->region_domain_name)
@section('maincontent')
<locationdevice url="{{ url('/') }}" :domain="{{ json_encode( $domain ) }}" :session="{{ json_encode( $getSession ) }}" />
@endsection
