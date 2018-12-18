<ul class="uk-nav uk-nav-default uk-nav-parent-icon" uk-nav>
  <li class="uk-nav-header">Master Data</li>
  <li><a href="{{ route('dashboardpage') }}">Dashboard</a></li>
  <li class="uk-parent"><a href="#">Zone Region & Domain</a>
    <ul class="uk-nav-sub">
      <li><a href="{{ route('zoneregionpage') }}">Region</a></li>
      <li><a href="{{ route('zonedomainpage') }}">Domain</a></li>
    </ul>
  </li>
  <li><a href="{{ route('devicespage') }}">Devices</a></li>
  <li class="uk-nav-header">Management</li>
  <li><a href="{{ route('usermikrotikpage') }}">Mikrotik Global User</a></li>
  <li><a href="{{ route('userpage') }}">Users</a></li>
  <li><a href="{{ route('logoutpage') }}">Logout</a></li>
</ul>
