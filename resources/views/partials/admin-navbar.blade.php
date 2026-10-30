<nav class="navbar navbar-dark bg-dark px-3">
    <a class="navbar-brand d-flex align-items-center"
       href="{{ route('admin.dashboard') }}">
        <img src="{{ asset('images/logo.svg') }}"
             alt="Logo"
             height="28"
             class="me-2">
        {{ $appName }}
    </a>

    @auth
        <div class="d-flex align-items-center">
            <div class="navbar-nav flex-row me-3">
                @can('manage-reports')
                <a class="nav-link px-2"
                   href="{{ route('admin.dashboard') }}">
                    Dashboard
                </a>
                @endcan
                @can('manage-users')    
                <a class="nav-link px-2"
                   href="{{ route('admin.users.index') }}">
                    Users
                </a>
                @endcan
                @can('manage-employees')
                <a class="nav-link px-2"
                   href="{{ route('admin.employees.index') }}">
                    Employees
                </a>
                @endcan
                @can('manage-departments')
                <a class="nav-link px-2"
                   href="{{ route('admin.departments.index') }}">
                    Departments
                </a>
                @endcan
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
