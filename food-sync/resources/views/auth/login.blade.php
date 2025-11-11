@extends('layouts.guest')

@section('title', 'Food Sync - Login')

@section('content')
    <span class="shadow-md fw-medium fs-4 text-center mb-4 px-3 py-2 ">Login to your Account</span>
    <div class="shadow-md p-4" style="width: 350px;">
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="text" class="form-control" name="email" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>

            <x-input-error :messages="$errors->get('email')" />
            <x-input-error :messages="$errors->get('password')" />

            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>

        <div class="text-center mt-2">
            <span>Don't have an account? <a href="{{ route('register') }}" class="text-decoration-none">Register here</a></span>
        </div>
    </div>
@endsection