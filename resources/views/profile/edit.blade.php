@extends('layouts.app')
@section('title', 'Edit My Profile')
@section('heading', 'Edit My Profile')

@section('content')
    <x-card title="Contact information">
        <p class="text-muted">You can update only your phone, address, and photo.</p>
        <form method="POST" data-validate-form action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <x-form.input name="phone" label="Phone" data-validate="required|phone" :value="$employee['phone']" />
            <x-form.input name="address" label="Address" :value="$employee['address']" />
            <x-form.input name="profile_photo" label="Profile Photo (jpg or png, max 2 MB)" type="file"
                          accept=".jpg,.jpeg,.png" data-validate="file" data-types="jpg,jpeg,png" data-max-kb="2048" />
            <div class="d-flex flex-wrap gap-2 mt-3">
                <button class="btn btn-primary">Save changes</button>
                <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </x-card>
@endsection
