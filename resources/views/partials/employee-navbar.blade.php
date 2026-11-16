<nav class="app-navbar">
    <div class="app-navbar-inner">
        <a class="app-brand" href="{{ route('profile.show') }}" aria-label="{{ $appName }} {{ __('messages.my_profile') }}">
            <img src="{{ asset('images/logo.svg') }}" alt="{{ $appName }}">
        </a>

        @auth
            <div class="app-navigation">
                <a class="nav-link {{ request()->routeIs('profile.department') ? 'active' : '' }}" href="{{ route('profile.department') }}">
                    <svg class="app-nav-icon" viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M9 20V8h6v12M8 11h.01M8 14h.01M16 11h.01M16 14h.01"/></svg>
                    {{ __('messages.my_department') }}
                </a>
                <a class="nav-link {{ request()->routeIs('profile.show', 'profile.edit') ? 'active' : '' }}" href="{{ route('profile.show', auth()->user()->employee->id) }}">
                    <svg class="app-nav-icon" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M5 21a7 7 0 0 1 14 0"/></svg>
                    {{ __('messages.my_profile') }}
                </a>
            </div>

            <div class="app-user-area">
                <span class="app-user-avatar" aria-hidden="true">
                    <svg class="app-nav-icon" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M5 21a7 7 0 0 1 14 0"/></svg>
                </span>
                <span class="app-user-name">{{ auth()->user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-light">{{ __('messages.sign_out') }}</button>
                </form>
            </div>
        @endauth

        @guest
            <a href="{{ route('login') }}" class="btn btn-sm btn-outline-light ms-auto">{{ __('messages.sign_in') }}</a>
        @endguest
    </div>
</nav>
