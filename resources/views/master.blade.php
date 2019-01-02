<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
@include('includes.meta-header')
<title>@yield('headtitle')</title>
</head>
<body>
  <div class="uk-grid-collapse" uk-grid>
    <div class="uk-width-1-5@xl uk-width-1-5@l uk-width-1-5@m uk-width-1-1@s">
      <nav class="mainnav" uk-height-viewport>
        <section class="uk-navbar uk-box-shadow-small submainnav" uk-navbar>
          <div class="uk-navbar-left">
            <a class="uk-navbar-item uk-logo" href="{{ url('/') }}">
              <img class="logo" src="{{ asset('images/logo/biznet_hotspot_white.png') }}" alt="">
            </a>
          </div>
          <div class="uk-navbar-right">
            <a class="uk-navbar-item nav-icon-offcanvas">
              <span uk-icon="menu"></span>
            </a>
          </div>
        </section>
        <section class="uk-card uk-card-body uk-card-small">
          <div class="uk-margin-top uk-margin-bottom profile_info">
            @if( empty( $users->profile_picture ) )
            <div class="uk-align-center uk-tile uk-tile-default uk-border-circle canvas_picture">
              <div class="uk-position-center">
                140 x 146
              </div>
            </div>
            @else
            <div class="uk-align-center uk-border-circle canvas_picture">
              <img src="{{ asset('images/avatar/' . $users->profile_picture) }}" alt="">
            </div>
            @endif
            <div class="profile_name">
              <a href="{{ route('userprofile_page') }}">{{ $users->fullname }}</a>
            </div>
            <div class="profile_privilege">
              @if( $users->privilege === 'admin' ) Administrator
              @else User
              @endif
            </div>
          </div>
          @include('includes.navleft')
        </section>
      </nav>
    </div>
    <div class="uk-width-expand">
      <div id="app">@yield('maincontent')</div>
    </div>
  </div>
<script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
</body>
</html>
