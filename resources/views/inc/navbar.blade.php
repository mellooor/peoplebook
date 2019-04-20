<nav id="main-nav" class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="{{ url('/') }}">{{ config('app.name', 'Laravel') }}</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        @guest
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                </li>
                @if (Route::has('register'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                    </li>
                @endif
            </ul>

        @else
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('notifications') }}">Notifications <span class="badge badge-pill badge-danger">1</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('friend-requests') }}">Friend Requests
                        @if ($navbarData['friend-request-count'] > 0)
                            <span id="friend-request-count" class="badge badge-pill badge-danger">{{ $navbarData['friend-request-count'] }}</span>
                        @else
                            <span id="friend-request-count" class="badge badge-pill badge-info">{{ $navbarData['friend-request-count'] }}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('user') }}">View My Profile</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('friends') }}">Friends</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('settings') }}">Settings</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        @endguest

        @auth
            <div style="width: 33%; display: flex;">
                <input id="search-term" class="form-control mr-sm-2" type="text" name="search" placeholder="Search..." aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" onclick="searchGet();">Search</button>
            </div>
        @endauth
    </div>
</nav>