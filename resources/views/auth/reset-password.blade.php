@extends('layouts.guest')

@section('title', 'Reset Password')

@section('content')
            <x-card title="Create a new password">
                <p class="text-secondary mb-4">Choose a secure password to regain access to your account.</p>

                <form method="POST" action="{{ route('password.store') }}">
                    @csrf

                    <input
                        type="hidden"
                        name="token"
                        value="{{ request()->route('token') }}"
                    >

                    <div class="mb-3">
                        <label for="reset-email" class="form-label">Email</label>

                        <input
                            id="reset-email"
                            type="email"
                            name="email"
                            class="form-control"
                            value="{{ old('email', request('email')) }}"
                            required
                            autofocus
                        >

                        @error('email')
                            <div class="text-danger mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="reset-password" class="form-label">New password</label>

                        <input
                            id="reset-password"
                            type="password"
                            name="password"
                            class="form-control"
                            required
                        >

                        @error('password')
                            <div class="text-danger mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="reset-password-confirmation" class="form-label">
                            Confirm new password
                        </label>

                        <input
                            id="reset-password-confirmation"
                            type="password"
                            name="password_confirmation"
                            class="form-control"
                            required
                        >
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Reset password</button>

                </form>
            </x-card>
@endsection