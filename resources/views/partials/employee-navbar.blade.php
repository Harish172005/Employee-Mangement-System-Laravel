@php
    $user = auth()->user();

    $notifications = $user->notifications()
        ->latest()
        ->take(5)
        ->get();

    $unreadCount = $user->unreadNotifications()->count();
@endphp


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

                <div class="employee-notifications dropdown">
                    <button class="nav-link employee-notifications-toggle"
                            type="button"
                            id="notificationDropdown"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                            aria-label="{{ __('messages.notifications') }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/>
                        </svg>
                        <span>{{ __('messages.notifications') }}</span>

                        @if ($unreadCount > 0)
                            <span class="employee-notification-count">
                                {{ $unreadCount }}
                                <span class="visually-hidden">{{ __('messages.unread_notifications') }}</span>
                            </span>
                        @endif
                    </button>

                    <div class="dropdown-menu dropdown-menu-end employee-notifications-menu"
                         aria-labelledby="notificationDropdown">

                        @forelse ($notifications as $notification)
                            @php
                                $isEmployeeUpdate = ($notification->data['type'] ?? '') === 'employee_updated';
                            @endphp
                            <article class="employee-notification-card {{ $isEmployeeUpdate ? 'is-profile-update' : 'is-general' }} {{ $notification->read_at ? '' : 'is-unread' }}">
                                <span class="employee-notification-icon" aria-hidden="true">
                                    @if ($isEmployeeUpdate)
                                        <svg viewBox="0 0 24 24">
                                            <circle cx="12" cy="8" r="4"/>
                                            <path d="M5 21a7 7 0 0 1 14 0"/>
                                        </svg>
                                    @else
                                        <svg viewBox="0 0 24 24">
                                            <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/>
                                        </svg>
                                    @endif
                                </span>
                                <div class="employee-notification-content">
                                    <div class="employee-notification-context">
                                        {{ $isEmployeeUpdate ? __('messages.employee_profile') : __('messages.notifications') }}
                                        @if (!$notification->read_at)
                                            <span class="employee-notification-new">{{ __('messages.new') }}</span>
                                        @endif
                                    </div>
                                    <h3 class="employee-notification-title">
                                        {{ $isEmployeeUpdate ? __('messages.profile_update_notification_title') : __('messages.new_notification') }}
                                    </h3>
                                    <p class="employee-notification-message">
                                        {{ $notification->data['message'] ?? __('messages.new_notification') }}
                                    </p>
                                    <time class="employee-notification-time" datetime="{{ $notification->created_at->toIso8601String() }}">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </time>
                                </div>
                            </article>
                        @empty
                            <p class="employee-notifications-empty">{{ __('messages.no_notifications') }}</p>
                        @endforelse

                    </div>
                </div>
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
