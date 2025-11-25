<x-guest-layout>
    <div class="max-w-md w-full space-y-8 p-8">
        <!-- Header -->
        <div class="text-center">
            <a href="{{ route('home') }}" class="text-2xl font-bold text-blue-600 mb-2 block">
                PCU-DASMA Connect
            </a>
            <h2 class="text-3xl font-bold text-gray-900">
                {{ __('Reset Password') }}
            </h2>
            <p class="mt-4 text-sm text-gray-600 leading-relaxed">
                {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
            </p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-md" :status="session('status')" />

        <!-- Forgot Password Form -->
        <form method="POST" action="{{ route('password.email') }}" class="mt-8 space-y-6">
            @csrf

            <div class="rounded-md shadow-sm">
                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email Address')" class="block text-sm font-medium text-gray-700 mb-1" />
                    <x-text-input
                        id="email"
                        class="w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600"
                        type="email"
                        name="email"
                        :value="old('email')"
                        placeholder="{{ __('Enter your email address') }}"
                        required
                        autofocus
                    />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
                </div>
            </div>

            <!-- Submit Button -->
            <div>
                <x-primary-button class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 transition-colors">
                    {{ __('Email Password Reset Link') }}
                </x-primary-button>
            </div>

            <!-- Back to Login Link -->
            <div class="text-center">
                <p class="text-sm text-gray-600">
                    {{ __('Remember your password?') }}
                    <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-700 transition-colors">
                        {{ __('Sign in here') }}
                    </a>
                </p>
            </div>
        </form>
    </div>
</x-guest-layout>
