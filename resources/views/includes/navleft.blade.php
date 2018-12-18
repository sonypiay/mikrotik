<ul class="uk-nav uk-nav-default uk-nav-parent-icon" uk-nav>
  <li><a href="{{ route('dashboardpage') }}"><span class="uk-margin-small-right" uk-icon="home"></span> Dashboard</a></li>
  <li><a href="{{ route('devicespage') }}"><span class="uk-margin-small-right" uk-icon="laptop"></span> Devices</a></li>
  <!--<li><a href="{{ route('usermikrotikpage') }}">Mikrotik Global User</a></li>-->
  <li><a @if( $request->route()->getName() === 'userpage' ) class="active" @endif href="{{ route('userpage') }}"><span class="uk-margin-small-right" uk-icon="user"></span> Users</a></li>
  <li class="uk-parent"><a><span class="uk-margin-small-right" uk-icon="world"></span> Zone Region & Domain</a>
    <ul class="uk-nav-sub">
      <li><a href="{{ route('zoneregionpage') }}">Region</a></li>
      <li><a href="{{ route('zonedomainpage') }}">Domain</a></li>
    </ul>
  </li>
  <li><a href="{{ route('logoutpage') }}"><span class="uk-margin-small-right" uk-icon="sign-out"></span> Logout</a></li>
</ul>
