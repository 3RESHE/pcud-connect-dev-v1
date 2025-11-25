<x-guest-layout>
    <div class="max-w-md w-full space-y-8 p-8">
        <!-- Header -->
        <div class="text-center">
            <a href="{{ route('home') }}" class="text-2xl font-bold text-blue-600 mb-2 block">
                PCU-DASMA Connect
            </a>
            <h2 class="text-3xl font-bold text-gray-900">
                {{ __('Set New Password') }}
            </h2>
            <p class="mt-4 text-sm text-gray-600 leading-relaxed">
                {{ __('Create a strong password to secure your account. Make sure to use a combination of letters, numbers, and special characters.') }}
            </p>
        </div>

        <!-- Reset Password Form -->
        <form method="POST" action="{{ route('password.store') }}" class="mt-8 space-y-6">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="rounded-md shadow-sm space-y-4">
                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email Address')" class="block text-sm font-medium text-gray-700 mb-1" />
                    <x-text-input
                        id="email"
                        class="w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 bg-gray-50 cursor-not-allowed"
                        type="email"
                        name="email"
                        :value="old('email', $request->email)"
                        placeholder="{{ __('Your email address') }}"
                        required
                        autofocus
                        autocomplete="username"
                        readonly
                    />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
                </div>

                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('New Password')" class="block text-sm font-medium text-gray-700 mb-1" />
                    <x-text-input
                        id="password"
                        class="w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600"
                        type="password"
                        name="password"
                        placeholder="{{ __('Enter new password') }}"
                        required
                        autocomplete="new-password"
                    />
                    <p class="mt-1 text-xs text-gray-500">
                        {{ __('Must be at least 8 characters long') }}
                    </p>
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="block text-sm font-medium text-gray-700 mb-1" />
                    <x-text-input
                        id="password_confirmation"
                        class="w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600"
                        type="password"
                        name="password_confirmation"
                        placeholder="{{ __('Confirm new password') }}"
                        required
                        autocomplete="new-password"
                    />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-red-600" />
                </div>
            </div>

            <!-- Password Requirements Info -->
            <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                <p class="text-sm font-medium text-blue-900 mb-2">{{ __('Password Requirements:') }}</p>
                <ul class="text-xs text-blue-800 space-y-1">
                    <li>✓ {{ __('At least 8 characters long') }}</li>
                    <li>✓ {{ __('Include uppercase letters (A-Z)') }}</li>
                    <li>✓ {{ __('Include lowercase letters (a-z)') }}</li>
                    <li>✓ {{ __('Include numbers (0-9)') }}</li>
                </ul>
            </div>

            <!-- Submit Button -->
            <div>
                <x-primary-button class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 transition-colors">
                    {{ __('Reset Password') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
