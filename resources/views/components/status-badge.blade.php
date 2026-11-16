@php($status = $status instanceof \BackedEnum ? $status->value : $status)
<span class="badge {{ $status === 'active' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($status) }}</span>
