<ul class="uk-nav uk-nav-default uk-nav-parent-icon" uk-nav>
  <li><a href="{{ route('dashboardpage') }}"><span class="uk-margin-small-right" uk-icon="home"></span> Dashboard</a></li>
  @if( $getSession['privilege'] === 'admin' )
  <li class="uk-parent"><a @if( $request->route()->getName() === 'userpage' || $request->route()->getName() === 'devicespage' || $request->route()->getName() === 'zoneregionpage' || $request->route()->getName() === 'zonedomainpage' ) class="active" @endif ><span class="uk-margin-small-right" uk-icon="world"></span> Management</a>
    <ul class="uk-nav-sub">
      <li><a href="{{ route('userpage') }}">Users</a></li>
      <li><a href="{{ route('devicespage') }}">Devices</a></li>
      <li><a href="{{ route('zoneregionpage') }}">Region</a></li>
      <li><a href="{{ route('zonedomainpage') }}">Domain</a></li>
    </ul>
  </li>
  @endif
  <li><a href="{{ route('logoutpage') }}"><span class="uk-margin-small-right" uk-icon="sign-out"></span> Logout</a></li>
</ul>
