@extends('v2.layouts.guest')

@section('title', 'Login')

@section('content')
    <div class="flex min-h-screen items-center justify-center bg-gray-50 px-4 py-12 sm:px-6 lg:px-8">
        <div class="w-full max-w-md space-y-8">
            <div>
                <div class="flex justify-center">
                    <i class="fas fa-clipboard-check text-4xl text-blue-600"></i>
                </div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Sign in to DeckCheck
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Or
                    <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-500">
                        create a new account
                    </a>
                </p>
            </div>

            <form class="mt-8 space-y-6" method="POST" action="{{ route('login') }}">
                @csrf

                <div class="space-y-4">
                    <x-v2-ui-input type="email" name="email" label="Email Address" :value="old('email')" required autofocus
                        :error="$errors->first('email')" />

                    <x-v2-ui-input type="password" name="password" label="Password" required :error="$errors->first('password')" />
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember-me" name="remember" type="checkbox"
                            class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <label for="remember-me" class="ml-2 block text-sm text-gray-900">
                            Remember me
                        </label>
                    </div>

                    <div class="text-sm">
                        <a href="{{ route('password.request') }}" class="font-medium text-blue-600 hover:text-blue-500">
                            Forgot your password?
                        </a>
                    </div>
                </div>

                <div>
                    <x-v2-ui-button type="submit" variant="primary" class="w-full">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Sign in
                    </x-v2-ui-button>
                </div>
            </form>
        </div>
    </div>
@endsection
