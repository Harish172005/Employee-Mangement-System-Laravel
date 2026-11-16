<nav class="app-navbar">
    <div class="app-navbar-inner">
        <a class="app-brand" href="{{ route('admin.dashboard') }}" aria-label="{{ $appName }} {{ __('messages.dashboard') }}">
            <img src="{{ asset('images/logo.svg') }}" alt="{{ $appName }}">
        </a>

        @auth
            <div class="app-navigation">
                @can('manage-reports')
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <svg class="app-nav-icon" viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></svg>
                        {{ __('messages.dashboard') }}
                    </a>
                @endcan
                @can('manage-users')
                    <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                        <svg class="app-nav-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="10" cy="7" r="4"/><path d="M20 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        {{ __('messages.users') }}
                    </a>
                @endcan
                @can('manage-employees')
                    <a class="nav-link {{ request()->routeIs('admin.employees.*') ? 'active' : '' }}" href="{{ route('admin.employees.index') }}">
                        <svg class="app-nav-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="10" cy="7" r="4"/><path d="M20 8v6m-3-3h6"/></svg>
                        {{ __('messages.employees') }}
                    </a>
                @endcan
                @can('manage-departments')
                    <a class="nav-link {{ request()->routeIs('admin.departments.*') ? 'active' : '' }}" href="{{ route('admin.departments.index') }}">
                        <svg class="app-nav-icon" viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M9 20V8h6v12M8 11h.01M8 14h.01M16 11h.01M16 14h.01"/></svg>
                        {{ __('messages.departments') }}
                    </a>
                @endcan
            </div>

            <div class="app-user-area">
                <a class="app-profile-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}"
                   href="{{ route('admin.profile.show') }}">
                    <span class="app-user-avatar" aria-hidden="true">
                        <svg class="app-nav-icon" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M5 21a7 7 0 0 1 14 0"/></svg>
                    </span>
                    <span class="app-user-name">{{ auth()->user()->name }}</span>
                </a>
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
