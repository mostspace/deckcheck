

@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<div class="flex items-center justify-center min-h-screen p-4 bg-[#f8f9fb]">
    <div class="w-full max-w-md">
        <!-- Logo and Branding -->
        <div class="text-center mb-8">
            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 bg-gradient-to-br from-[#6840c6] to-[#7e56d8] rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fa-solid fa-clipboard-check text-white text-2xl"></i>
                </div>
            </div>
            <h1 class="text-[#0f1728] text-2xl font-bold mb-2">Welcome back</h1>
            <p class="text-[#475466] text-sm">Sign in to your DeckCheck account</p>
        </div>

        <!-- Login Form -->
        <div class="bg-white rounded-lg shadow border border-[#e4e7ec] p-6">
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <!-- Email -->
                <div>
                    <label for="email" class="text-[#344053] text-sm leading-tight">Email</label>
                    <div class="mt-1 relative">
                        <input id="email" name="email" type="email" required autofocus placeholder="Enter your email"
                            class="w-full px-3.5 py-2.5 rounded-lg border border-[#cfd4dc] shadow text-base text-[#0f1728] placeholder-[#667084] focus:outline-none">
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="text-[#344053] text-sm leading-tight">Password</label>
                    <div class="mt-1 relative">
                        <input id="password" name="password" type="password" required placeholder="Enter your password"
                            class="w-full px-3.5 py-2.5 rounded-lg border border-[#cfd4dc] shadow text-base text-[#0f1728] placeholder-[#667084] focus:outline-none">
                        <i id="toggle-password" class="fa-solid fa-eye absolute right-3 top-3 text-[#667084] cursor-pointer"></i>
                    </div>
                </div>

                <!-- Remember Me + Forgot Password -->
                <div class="flex justify-between items-center text-sm">
                    <label class="flex items-center gap-2 text-[#344053]">
                        <input type="checkbox" name="remember" class="rounded border-[#cfd4dc]">
                        Remember me
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-[#6840c6] hover:text-[#7e56d8]">Forgot password?</a>
                    @endif
                </div>

                <!-- Submit -->
                <div>
                    <button type="submit" class="w-full px-4 py-2.5 bg-[#7e56d8] text-white rounded-lg shadow hover:bg-[#6840c6] transition-colors">
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
            </div>--}}
        </div>

        <!-- Support Links -->
        <div class="flex justify-center gap-6 mt-8 text-sm text-[#475466]">
            <a href="#" class="flex items-center gap-2 hover:text-[#6840c6]">
                <i class="fa-solid fa-envelope"></i> Contact Us
            </a>
            <a href="#" class="flex items-center gap-2 hover:text-[#6840c6]">
                <i class="fa-solid fa-life-ring"></i> Support
            </a>
        </div>

        <!-- Footer -->
        <div class="text-center mt-6 text-xs text-[#667084]">
            © {{ now()->year }} DeckCheck. All rights reserved.
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('toggle-password')?.addEventListener('click', function () {
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