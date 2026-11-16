@php($status = $status instanceof \BackedEnum ? $status->value : $status)
<span class="badge status-badge {{ $status === 'active' ? 'bg-success' : 'bg-secondary' }}">{{ __('messages.' . strtolower($status)) }}</span>
