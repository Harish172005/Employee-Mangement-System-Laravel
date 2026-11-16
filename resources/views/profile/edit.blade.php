@extends('layouts.app')
@section('title', __('messages.profile_edit_title'))
@section('heading', __('messages.profile_edit_title'))

@section('content')
    <x-card :title="__('messages.contact_information')">
        <p class="text-muted">{{ __('messages.contact_information_help') }}</p>
        <form method="POST" data-validate-form action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <x-form.input name="phone" :label="__('messages.phone')" data-validate="required|phone" :value="$employee['phone']" />
            <x-form.input name="address" :label="__('messages.address')" :value="$employee['address']" />
            <x-form.input name="profile_photo" :label="__('messages.profile_photo')" type="file"
                          accept=".jpg,.jpeg,.png" data-validate="file" data-types="jpg,jpeg,png" data-max-kb="2048" />
            <div class="d-flex flex-wrap gap-2 mt-3">
                <button class="btn btn-primary">{{ __('messages.save_changes') }}</button>
                <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">{{ __('messages.cancel') }}</a>
            </div>
        </form>
    </x-card>
@endsection
