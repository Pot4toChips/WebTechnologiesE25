@extends('layouts.guest')

@section('title', 'Food Sync - Register')

@section('content')
    <span class="shadow-md fw-medium fs-4 text-center mb-4 px-3 py-2 ">Create your Account</span>
    <div class="shadow-md p-4" style="width: 400px;">
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Username</label>
                <input type="text" class="form-control" name="name" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="text" class="form-control" name="email" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>

            <div class="mb-3">
                <label for="confirm-password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" name="confirm-password" required>
            </div>

            <x-input-error :messages="$errors->get('name')" class="mb-3" />
            <x-input-error :messages="$errors->get('email')" class="mb-3" />
            <x-input-error :messages="$errors->get('password')" class="mb-3" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mb-3" />

            <button type="submit" class="btn btn-primary w-100">Register</button>
        </form>

        <div class="text-center mt-2">
            <span>Already have an account? <a href="{{ route('login') }}" class="text-decoration-none">Login here</a></span>
        </div>
    </div>
@endsection