@extends('layouts.app')
@section('title', 'Users')
@section('heading', 'Users')

@section('content')
    <div class="user-page-toolbar">
        <div>
            <h2 class="user-page-title">Account directory</h2>
            <p class="user-page-description">Manage access, roles, and account status for your team.</p>
        </div>
    </div>

    <x-card title="All accounts">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 user-table">
                <thead>
                    <tr>
                        <th>Team member</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        @php($role = $user['role'] instanceof \BackedEnum ? $user['role']->value : $user['role'])
                        <tr>
                            <td>
                                <div class="user-table-person">
                                    <span class="user-table-avatar" aria-hidden="true">{{ strtoupper(substr($user['name'], 0, 1)) }}</span>
                                    <span class="user-table-person-copy">
                                        <strong>{{ $user['name'] }}</strong>
                                        <span>{{ $user['email'] }}</span>
                                    </span>
                                </div>
                            </td>
                            <td><span class="user-username">{{ '@' . $user['username'] }}</span></td>
                            <td><span class="role-badge {{ strtolower($role) === 'admin' ? 'role-badge-admin' : 'role-badge-employee' }}">{{ ucfirst($role) }}</span></td>
                            <td><x-status-badge :status="$user['status']" /></td>
                            <td class="text-secondary">{{ $user['created_at'] }}</td>
                            <td class="table-actions">
                                @unless ($user['status'] === 'inactive')
                                    <form action="{{ route('admin.users.destroy', $user['username']) }}" method="POST" class="d-inline"
                                          data-confirm="Deactivate this user?">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Deactivate</button>
                                    </form>
                                @endunless
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="user-empty-state">
                                    <span class="user-empty-icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="10" cy="7" r="4"/><path d="M20 8v6m-3-3h6"/></svg>
                                    </span>
                                    <strong>No accounts yet</strong>
                                    <span>Create a user account to get your team started.</span>
                                    <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-primary mt-2">Add first user</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
    <div id="user-pagination">{{ $users->links() }}</div>
@endsection
