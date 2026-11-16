@props(['name', 'label', 'options' => [], 'selected' => null, 'placeholder' => '-- Select --'])
<div class="mb-3">
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    <select id="{{ $name }}" name="{{ $name }}"
            {{ $attributes->merge(['class' => 'form-select' . ($errors->has($name) ? ' is-invalid' : '')]) }}>
        @if ($placeholder !== null)
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach ($options as $key => $text)
            <option value="{{ $key }}" @selected((string) old($name, $selected) === (string) $key)>{{ $text }}</option>
        @endforeach
    </select>
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
