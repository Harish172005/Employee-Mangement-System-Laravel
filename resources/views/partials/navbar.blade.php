<nav class="navbar navbar-dark bg-dark px-3">
    <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
        <img src="{{ asset('images/logo.svg') }}" alt="Logo" height="28" class="me-2">
        {{ $appName }}
    </a>
    <div>
        @auth
            <span class="text-light me-3">{{ auth()->user()->name }}</span>
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button class="btn btn-outline-light btn-sm">Logout</button>
            </form>
        @endauth
        @guest
            <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">Login</a>
        @endguest
    </div>
</nav>
