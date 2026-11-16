@extends('layouts.app')
@section('title', __('messages.users'))
@section('heading', __('messages.users'))

@section('content')
    <div class="user-page-toolbar">
        <div>
            <h2 class="user-page-title">{{ __('messages.account_directory') }}</h2>
            <p class="user-page-description">{{ __('messages.manage_team_accounts') }}</p>
        </div>
    </div>

    <x-card :title="__('messages.all_accounts')">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 user-table">
                <thead>
                    <tr>
                        <th>{{ __('messages.team_member') }}</th>
                        <th>{{ __('messages.username') }}</th>
                        <th>{{ __('messages.role') }}</th>
                        <th>{{ __('messages.status') }}</th>
                        <th>{{ __('messages.joined') }}</th>
                        <th>{{ __('messages.actions') }}</th>
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
                            <td><span class="role-badge {{ strtolower($role) === 'admin' ? 'role-badge-admin' : 'role-badge-employee' }}">{{ __('messages.' . strtolower($role)) }}</span></td>
                            <td><x-status-badge :status="$user['status']" /></td>
                            <td class="text-secondary">{{ $user['created_at'] }}</td>
                            <td class="table-actions">
                                @unless ($user['status'] === 'inactive')
                                    <form action="{{ route('admin.users.destroy', $user['username']) }}" method="POST" class="d-inline"
                                          data-confirm="{{ __('messages.deactivate_user_confirm') }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">{{ __('messages.deactivate') }}</button>
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
                                    <strong>{{ __('messages.no_accounts_yet') }}</strong>
                                    <span>{{ __('messages.create_user_account') }}</span>
                                    <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-primary mt-2">{{ __('messages.add_first_user') }}</a>
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
