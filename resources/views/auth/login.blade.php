<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="max-w-md w-full space-y-8 p-8">
        <!-- Header -->
        <div class="text-center">
            <a href="{{ route('home') }}" class="text-2xl font-bold text-blue-600 mb-2 block">
                PCU-DASMA Connect
            </a>
            <h2 class="text-3xl font-bold text-gray-900">
                {{ __('Sign in to your account') }}
            </h2>
        </div>

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-6">
            @csrf

            <div class="rounded-md shadow-sm space-y-4">
                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email Address')" class="block text-sm font-medium text-gray-700 mb-1" />
                    <x-text-input
                        id="email"
                        class="w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600"
                        type="email"
                        name="email"
                        :value="old('email')"
                        placeholder="{{ __('Enter your email') }}"
                        required
                        autofocus
                        autocomplete="username"
                    />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('Password')" class="block text-sm font-medium text-gray-700 mb-1" />
                    <x-text-input
                        id="password"
                        class="w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600"
                        type="password"
                        name="password"
                        placeholder="{{ __('Enter your password') }}"
                        required
                        autocomplete="current-password"
                    />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input
                        id="remember_me"
                        name="remember"
                        type="checkbox"
                        class="h-4 w-4 text-blue-600 focus:ring-blue-600 border-gray-300 rounded"
                    />
                    <label for="remember_me" class="ml-2 block text-sm text-gray-700">
                        {{ __('Remember me') }}
                    </label>
                </div>
                <div class="text-sm">
                    @if (Route::has('password.request'))
                        <a
                            href="{{ route('password.request') }}"
                            class="font-medium text-blue-600 hover:text-blue-700 transition-colors"
                        >
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif
                </div>
            </div>

            <!-- Submit Button -->
            <div>
                <x-primary-button class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 transition-colors">
                    {{ __('Sign in here') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
