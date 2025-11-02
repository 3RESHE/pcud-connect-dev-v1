@extends('layouts.app')

@section('title', 'Change Password - First Login')

@section('content')
<div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow">
    <h1 class="text-2xl font-bold mb-6">Change Your Password</h1>

    <p class="text-gray-600 mb-6">This is your first login. Please set a new password for your account.</p>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <div class="mb-4">
            <label for="current_password" class="block text-gray-700 font-bold mb-2">Current Password</label>
            <input type="password" id="current_password" name="current_password" required
                class="w-full px-3 py-2 border border-gray-300 rounded">
            @error('current_password')
                <span class="text-red-600 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password" class="block text-gray-700 font-bold mb-2">New Password</label>
            <input type="password" id="password" name="password" required
                class="w-full px-3 py-2 border border-gray-300 rounded">
            @error('password')
                <span class="text-red-600 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-6">
            <label for="password_confirmation" class="block text-gray-700 font-bold mb-2">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required
                class="w-full px-3 py-2 border border-gray-300 rounded">
            @error('password_confirmation')
                <span class="text-red-600 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 rounded hover:bg-blue-700">
            Update Password
        </button>
    </form>
</div>
@endsection
