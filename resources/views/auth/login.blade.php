@extends('layouts.guest')

@section('title', 'Login')

@section('content')
    <div class="flex min-h-screen items-center justify-center bg-[#f8f9fb] p-4">
        <div class="w-full max-w-md">
            <!-- Logo and Branding -->
            <div class="mb-8 text-center">
                <div class="mb-6 flex justify-center">
                    <div
                        class="flex h-16 w-16 items-center justify-center rounded-xl bg-gradient-to-br from-[#6840c6] to-[#7e56d8] shadow-lg">
                        <i class="fa-solid fa-clipboard-check text-2xl text-white"></i>
                    </div>
                </div>
                <h1 class="mb-2 text-2xl font-bold text-[#0f1728]">Welcome back</h1>
                <p class="text-sm text-[#475466]">Sign in to your DeckCheck account</p>
            </div>

            <!-- Login Form -->
            <div class="rounded-lg border border-[#e4e7ec] bg-white p-6 shadow">
                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf
                    <!-- Email -->
                    <div>
                        <label for="email" class="text-sm leading-tight text-[#344053]">Email</label>
                        <div class="relative mt-1">
                            <input id="email" name="email" type="email" required autofocus
                                placeholder="Enter your email"
                                class="w-full rounded-lg border border-[#cfd4dc] px-3.5 py-2.5 text-base text-[#0f1728] placeholder-[#667084] shadow focus:outline-none">
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="text-sm leading-tight text-[#344053]">Password</label>
                        <div class="relative mt-1">
                            <input id="password" name="password" type="password" required placeholder="Enter your password"
                                class="w-full rounded-lg border border-[#cfd4dc] px-3.5 py-2.5 text-base text-[#0f1728] placeholder-[#667084] shadow focus:outline-none">
                            <i id="toggle-password"
                                class="fa-solid fa-eye absolute right-3 top-3 cursor-pointer text-[#667084]"></i>
                        </div>
                    </div>

                    <!-- Remember Me + Forgot Password -->
                    <div class="flex items-center justify-between text-sm">
                        <label class="flex items-center gap-2 text-[#344053]">
                            <input type="checkbox" name="remember" class="rounded border-[#cfd4dc]">
                            Remember me
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-[#6840c6] hover:text-[#7e56d8]">Forgot
                                password?</a>
                        @endif
                    </div>

                    <!-- Submit -->
                    <div>
                        <button type="submit"
                            class="w-full rounded-lg bg-[#7e56d8] px-4 py-2.5 text-white shadow transition-colors hover:bg-[#6840c6]">
                            Sign in
                        </button>
                    </div>
                </form>

                {{--
            <!-- Divider -->
            <div class="flex items-center my-6">
                <div class="flex-1 h-px bg-[#e4e7ec]"></div>
                <span class="px-4 text-[#667084] text-sm">or</span>
                <div class="flex-1 h-px bg-[#e4e7ec]"></div>
            </div>

            <!-- Register -->
            <div class="text-center text-sm text-[#475466]">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-[#6840c6] hover:text-[#7e56d8] font-medium">Sign up</a>
            </div> --}}
            </div>

            <!-- Support Links -->
            <div class="mt-8 flex justify-center gap-6 text-sm text-[#475466]">
                <a href="#" class="flex items-center gap-2 hover:text-[#6840c6]">
                    <i class="fa-solid fa-envelope"></i> Contact Us
                </a>
                <a href="#" class="flex items-center gap-2 hover:text-[#6840c6]">
                    <i class="fa-solid fa-life-ring"></i> Support
                </a>
            </div>

            <!-- Footer -->
            <div class="mt-6 text-center text-xs text-[#667084]">
                © {{ now()->year }} DeckCheck. All rights reserved.
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.getElementById('toggle-password')?.addEventListener('click', function() {
                const input = document.getElementById('password');
                const icon = this;
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.replace('fa-eye', 'fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.replace('fa-eye-slash', 'fa-eye');
                }
            });
        </script>
    @endpush
@endsection
