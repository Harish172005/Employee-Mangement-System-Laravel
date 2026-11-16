@if (session('success'))
    <x-alert type="success">{{ session('success') }}</x-alert>
@endif

@if (session('error'))
    <x-alert type="danger">{{ session('error') }}</x-alert>
@endif

@if ($errors->any())
    <x-alert type="danger">
        <strong>Please fix the following:</strong>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </x-alert>
@endif
