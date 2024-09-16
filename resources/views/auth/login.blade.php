<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="px-6 py-4">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block w-full mt-1" type="email" name="email" :value="old('email')" required
                autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block w-full mt-1" type="password" name="password" required
                autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="text-green-600 border-green-300 rounded shadow-sm dark:bg-green-900 dark:border-green-700 focus:ring-green-500 dark:focus:ring-green-600 dark:focus:ring-offset-green-800"
                    name="remember">
                <span class="text-sm text-green-900 ms-2 dark:text-green-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-4">
            <a wire:navigate class="text-sm text-green-600 underline rounded-md dark:text-green-400 hover:text-green-900 dark:hover:text-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:focus:ring-offset-green-800"
                href="{{ route('password.request') }}">
                {{ __('Forgot your password?') }}
            </a>

            <a wire:navigate class="text-sm text-green-600 underline rounded-md dark:text-green-400 hover:text-green-900 dark:hover:text-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:focus:ring-offset-green-800"
                href="{{ route('register') }}">
                {{ __('Already registered?') }}
            </a>
        </div>

        <div class="flex justify-end mt-4">
            <x-primary-button>
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
