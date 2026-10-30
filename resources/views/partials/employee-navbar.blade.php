<nav class="navbar navbar-dark bg-dark px-3">
    <a class="navbar-brand d-flex align-items-center"
       href="{{ route('profile.show') }}">
        <img src="{{ asset('images/logo.svg') }}"
             alt="Logo"
             height="28"
             class="me-2">
        {{ $appName }}
    </a>

    @auth
        <div class="d-flex align-items-center">
            <div class="navbar-nav flex-row me-3">
                <a class="nav-link px-2"
                   href="{{ route('profile.department') }}">
                    Department
                </a>    
                <a class="nav-link px-2"
                   href="{{ route('profile.show') }}">
                    Users
                </a>
            </div>

            <span class="text-light me-3">
                {{ auth()->user()->name }}
            </span>

            <form action="{{ route('logout') }}"
                  method="POST"
                  class="d-inline">
                @csrf
                <button type="submit"
                        class="btn btn-outline-light btn-sm">
                    Logout
                </button>
            </form>
        </div>
    @endauth

    @guest
        <a href="{{ route('login') }}"
           class="btn btn-outline-light btn-sm">
            Login
        </a>
    @endguest
</nav>

