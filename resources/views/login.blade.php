<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
@include('includes.meta-header')
<title>Mikrotik Controller | Login</title>
</head>
<body>
<div id="app">
  <loginsection url="{{ url('/') }}"></loginsection>
</div>
<script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
</body>
</html>
