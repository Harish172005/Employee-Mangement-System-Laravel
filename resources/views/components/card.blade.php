@props(['title' => null])
<div {{ $attributes->merge(['class' => 'card shadow-sm mb-3']) }}>
    @if ($title)
        <div class="card-header fw-semibold">{{ $title }}</div>
    @endif
    <div class="card-body">{{ $slot }}</div>
</div>
