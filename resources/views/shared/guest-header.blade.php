<header class="guest-header">
    <div class="container guest-header__content">
        <div class="guest-header__logo">
            <span class="guest-header__logo-graphic">
                <img src="{{asset('images/logo.png')}}">
            </span>
            <span class="guest-header__logo-words">Ping The People</span>
        </div>

        <nav class="guest-header__nav">
            <ul class="guest-header__nav-list">
                <li class="guest-header__nav-item {{ (Request::is('login') ? 'is-active' : '') }}">
                    <a href="{{url('/login')}}">Log in</a>
                </li>
                <li class="guest-header__nav-item {{ (Request::is('about') ? 'is-active' : '') }}">
                    <a href="{{url('/about')}}">About</a>
                </li>
            </ul>
        </nav>
    </div>
</header>