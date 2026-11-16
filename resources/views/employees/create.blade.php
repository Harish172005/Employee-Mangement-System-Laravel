@extends('layouts.app')
@section('title', __('messages.add_employee'))
@section('heading', __('messages.add_employee'))

@section('content')
    <x-card :title="__('messages.employee_information')">
        <form method="POST" data-validate-form action="{{ route('admin.employees.store') }}" enctype="multipart/form-data">
            @csrf
            @include('employees._form', ['employee' => null])
            <div class="d-flex flex-wrap gap-2 mt-3">
                <button class="btn btn-primary">{{ __('messages.save_employee') }}</button>
                <a href="{{ route('admin.employees.index') }}" class="btn btn-outline-secondary">{{ __('messages.cancel') }}</a>
            </div>
        </form>
    </x-card>
@endsection
