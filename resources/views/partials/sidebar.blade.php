{{-- Module 8: wrap the Admin links in @can('manage-employees') ... @endcan
     and show only the "My Account" links to employees. --}}
<div class="list-group list-group-flush">
    <a href="{{ route('dashboard') }}"
       class="list-group-item list-group-item-action {{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>

    <div class="list-group-item small text-uppercase text-muted">Admin</div>
    <a href="{{ route('admin.employees.index') }}"
       class="list-group-item list-group-item-action {{ request()->routeIs('admin.employees.*') ? 'active' : '' }}">Employees</a>
    <a href="{{ route('admin.departments.index') }}"
       class="list-group-item list-group-item-action {{ request()->routeIs('admin.departments.*') ? 'active' : '' }}">Departments</a>
    <a href="{{ route('admin.users.index') }}"
       class="list-group-item list-group-item-action {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">Users</a>
    <a href="{{ route('admin.reports') }}"
       class="list-group-item list-group-item-action {{ request()->routeIs('admin.reports') ? 'active' : '' }}">Reports</a>

    <div class="list-group-item small text-uppercase text-muted">My Account</div>
    <a href="{{ route('profile.show') }}"
       class="list-group-item list-group-item-action {{ request()->routeIs('profile.show', 'profile.edit') ? 'active' : '' }}">My Profile</a>
    <a href="{{ route('profile.department') }}"
       class="list-group-item list-group-item-action {{ request()->routeIs('profile.department') ? 'active' : '' }}">My Department</a>
    <a href="{{ route('profile.password.edit') }}"
       class="list-group-item list-group-item-action {{ request()->routeIs('profile.password.*') ? 'active' : '' }}">Change Password</a>
</div>
