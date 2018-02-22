<ul class="nav-list">
    <li class="nav-item {{ (Request::is('/') ? 'is-active' : '') }}">
        <a href="{{url('/')}}">My watch list</a>
    </li>
    <li class="nav-item {{ (Request::is('bills/*') || Request::is('bills') ? 'is-active' : '') }}">
        <a href="{{url('/bills')}}">All legislation</a>
    </li>
    <li class="nav-item {{ (Request::is('account') ? 'is-active' : '') }}">
        <a href="{{url('/account')}}">Settings</a>
    </li>
    <li class="nav-item {{ (Request::is('support') ? 'is-active' : '') }}">
        <a href="{{url('/support')}}">Donate</a>
    </li>
    <li class="nav-item {{ (Request::is('logout') ? 'is-active' : '') }}">
        <a href="{{ url('/logout') }}">Logout</a>
    </li>
</ul>