<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        @session('status')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ $value }}
            </div>
        @endsession

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mt-4">
                <x-label for="user_role" value="{{ __('Role') }}" />
                <select id="user_role" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" name="user_role" required>
                    <option value="buyer" {{ old('user_role') == 'buyer' ? 'selected' : '' }}>Buyer</option>
                    <option value="seller" {{ old('user_role') == 'seller' ? 'selected' : '' }}>Seller</option>
                </select>
            </div>

            <div class="mt-4">
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block bg-brown-50 mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" class="block bg-brown-50 mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            </div>



            <div class="flex justify-between items-center mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" />
                    <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
                @if (Route::has('password.request'))
                    <a class="underline mt-1 text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button class="ms-4">
                    {{ __('sign in') }}
                </x-button>
            </div>

            <div class="mt-10">
                <p class="text-sm flex justify-center text-gray-600">
                    {{ __('Don\'t have an account?') }}
                    <a href="{{ route('register') }}" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md">
                        {{ __('Register') }}
                    </a>
                </p>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
