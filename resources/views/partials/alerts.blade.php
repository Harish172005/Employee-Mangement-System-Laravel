@if (session('success'))
    <x-alert type="success">{{ session('success') }}</x-alert>
@endif

@if (session('error'))
    <x-alert type="danger">{{ session('error') }}</x-alert>
@endif

@if ($errors->any())
    <x-alert type="danger">
        <strong>{{ __('messages.please_fix_following') }}</strong>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </x-alert>
@endif
