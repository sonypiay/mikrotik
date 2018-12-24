@extends('master')
@section('headtitle', $users->fullname)
@section('maincontent')
<userprofile url="{{ url('/') }}" :users="{{ json_encode( $users ) }}" />
@endsection
