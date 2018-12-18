<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
@include('includes.meta-header')
<title>@yield('headtitle')</title>
</head>
<body>
<header class="uk-navbar uk-box-shadow-small navbar">
  <div class="uk-navbar-left">
    <a class="uk-navbar-item uk-logo" href="#">Mikrotik Controller</a>
  </div>
  <nav class="uk-navbar-right">
    <ul class="uk-navbar-nav navtop">
      <li><a href="#">Welcome, John Doe</a></li>
    </ul>
  </nav>
</header>
<div class="uk-grid-collapse" uk-grid>
  <div class="uk-width-1-5@xl uk-width-1-5@l uk-width-1-5@m uk-width-1-1@s">
    <nav class="uk-card uk-card-body uk-card-default" uk-height-viewport>
      @include('includes.navleft')
    </nav>
  </div>
  <div class="uk-width-expand">
    <section class="uk-padding-small uk-margin-small-top">
      <div id="app">@yield('maincontent')</div>
    </section>
  </div>
</div>
<script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
</body>
</html>
