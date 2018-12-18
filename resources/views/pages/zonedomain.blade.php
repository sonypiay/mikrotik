@extends('master')
@section('headtitle','Mikrotik Controller - Zone Domain')
@section('maincontent')
<zonedomain url="{{ url('/') }}" v-bind:region="{{ json_encode( $zoneregion ) }}"></zonedomain>
@endsection
