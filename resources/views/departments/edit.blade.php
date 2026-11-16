@extends('layouts.app')
@section('title', __('messages.edit_department'))
@section('heading', __('messages.edit_department'))

@section('content')
    <x-card :title="__('messages.department_information')">
        <form method="POST" data-validate-form action="{{ route('admin.departments.update', $department) }}">
            @csrf
            @method('PUT')
            @include('departments._form', ['department' => $department])
            <div class="d-flex flex-wrap gap-2 mt-3">
                <button class="btn btn-primary">{{ __('messages.save_changes') }}</button>
                <a href="{{ route('admin.departments.index') }}" class="btn btn-outline-secondary">{{ __('messages.cancel') }}</a>
            </div>
        </form>
    </x-card>
@endsection
