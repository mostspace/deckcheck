@extends('layouts.guest')

@section('title', 'Welcome | Step 1: Personal Information')

@section('content')

    <!-- Fullscreen Centered Container -->
    <div class="flex min-h-screen items-center justify-center bg-gray-50 px-4">
        <!-- Registration Container -->
        <div class="w-full max-w-3xl overflow-hidden rounded-xl bg-white shadow-lg">

            <!-- Progress Indicator -->
            <div class="border-b border-[#e4e7ec] bg-[#f8f9fb] p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="relative h-12 w-12">
                            <div class="h-12 w-12 animate-spin rounded-full border-4 border-[#7e56d8] border-r-transparent">
                            </div>
                            <div
                                class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 transform text-sm font-medium">
                                1/3
                            </div>
                        </div>
                        <h2 class="text-2xl font-bold text-[#0f1728]">Personal Information</h2>
                    </div>
                </div>
            </div>

            {{-- @include('components.registration.inviting-vessel-summary') --}}

            <!-- Form Content -->
            <div class="p-6">
                <form method="POST" action="{{ route('invitations.accept.password') }}" class="space-y-6" id="acceptForm">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <!-- Name Fields -->
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div class="flex flex-col items-start justify-start gap-1.5">
                            <label for="first_name" class="text-sm font-medium text-[#344053]">First Name</label>
                            <div
                                class="flex items-center gap-2 self-stretch rounded-lg border border-[#cfd4dc] bg-[#f9fafb] px-3.5 py-2.5 shadow">
                                <input id="first_name" name="first_name" type="text"
                                    value="{{ $invitation->boarding->user->first_name }}"
                                    class="grow bg-transparent text-base text-[#98a2b3] outline-none" readonly disabled>
                            </div>
                        </div>
                        <div class="flex flex-col items-start justify-start gap-1.5">
                            <label for="last_name" class="text-sm font-medium text-[#344053]">Last Name</label>
                            <div
                                class="flex items-center gap-2 self-stretch rounded-lg border border-[#cfd4dc] bg-[#f9fafb] px-3.5 py-2.5 shadow">
                                <input id="last_name" name="last_name" type="text"
                                    value="{{ $invitation->boarding->user->last_name }}"
                                    class="grow bg-transparent text-base text-[#98a2b3] outline-none" readonly disabled>
                            </div>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="flex flex-col items-start justify-start gap-1.5">
                        <label for="email" class="text-sm font-medium text-[#344053]">Email</label>
                        <div
                            class="flex items-center gap-2 self-stretch rounded-lg border border-[#cfd4dc] bg-[#f9fafb] px-3.5 py-2.5 shadow">
                            <input id="email" name="email" type="email"
                                value="{{ $invitation->boarding->user->email }}"
                                class="grow bg-transparent text-base text-[#98a2b3] outline-none" readonly disabled>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="flex flex-col items-start justify-start gap-1.5">
                        <label for="phone" class="text-sm font-medium text-[#344053]">Phone Number</label>
                        <div
                            class="flex items-center gap-2 self-stretch rounded-lg border border-[#cfd4dc] bg-[#f9fafb] px-3.5 py-2.5 shadow">
                            <input id="phone" name="phone" type="tel"
                                value="{{ $invitation->boarding->user->phone }}"
                                class="grow bg-transparent text-base text-[#98a2b3] outline-none" readonly disabled>
                        </div>
                    </div>

                    <!-- Password Fields -->
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div class="flex flex-col items-start justify-start gap-1.5">
                            <label for="password" class="text-sm font-medium text-[#344053]">Password</label>
                            <div
                                class="flex items-center gap-2 self-stretch rounded-lg border border-[#cfd4dc] bg-white px-3.5 py-2.5 shadow">
                                <input id="password" name="password" type="password"
                                    class="grow bg-transparent text-base text-[#0f1728] outline-none"
                                    placeholder="Create a password" required>
                                <button type="button" class="text-[#667084]" onclick="toggleVisibility('password', this)">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="flex flex-col items-start justify-start gap-1.5">
                            <label for="password_confirmation" class="text-sm font-medium text-[#344053]">Confirm
                                Password</label>
                            <div
                                class="flex items-center gap-2 self-stretch rounded-lg border border-[#cfd4dc] bg-white px-3.5 py-2.5 shadow">
                                <input id="password_confirmation" name="password_confirmation" type="password"
                                    class="grow bg-transparent text-base text-[#0f1728] outline-none"
                                    placeholder="Confirm your password" required>
                                <button type="button" class="text-[#667084]"
                                    onclick="toggleVisibility('password_confirmation', this)">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Password Requirements -->
                    <div class="rounded-lg border border-[#e4e7ec] bg-[#f8f9fb] p-4">
                        <div class="mb-2 text-sm font-medium text-[#344053]">Password Requirements:</div>
                        <ul id="passwordRules" class="space-y-2 text-sm text-[#98a2b3]">
                            <li class="rule flex items-center" data-rule="length">
                                <i class="fa-solid fa-check mr-2"></i> At least 8 characters long
                            </li>
                            <li class="rule flex items-center" data-rule="uppercase">
                                <i class="fa-solid fa-check mr-2"></i> Contains at least one uppercase letter
                            </li>
                            <li class="rule flex items-center" data-rule="number">
                                <i class="fa-solid fa-check mr-2"></i> Contains at least one number
                            </li>
                            <li class="rule flex items-center" data-rule="match">
                                <i class="fa-solid fa-check mr-2"></i> Passwords match
                            </li>
                        </ul>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end border-t border-[#e4e7ec] pt-6">
                        <button type="submit" id="submitButton"
                            class="flex items-center gap-2 rounded-lg border border-[#7e56d8] bg-[#7e56d8] px-[18px] py-2.5 font-medium text-white shadow disabled:opacity-50"
                            disabled>
                            Continue
                            <i class="fa-solid fa-arrow-right text-white"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script>
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('password_confirmation');
        const rules = {
            length: false,
            uppercase: false,
            number: false,
            match: false
        };

        function updateUI() {
            for (const rule in rules) {
                const el = document.querySelector(`[data-rule="${rule}"]`);
                el.classList.toggle('text-[#12b669]', rules[rule]);
                el.classList.toggle('text-[#98a2b3]', !rules[rule]);
            }
            document.getElementById('submitButton').disabled = !Object.values(rules).every(Boolean);
        }

        function validate() {
            const pwd = passwordInput.value;
            const conf = confirmInput.value;
            rules.length = pwd.length >= 8;
            rules.uppercase = /[A-Z]/.test(pwd);
            rules.number = /[0-9]/.test(pwd);
            rules.match = pwd && pwd === conf;
            updateUI();
        }

        passwordInput.addEventListener('input', validate);
        confirmInput.addEventListener('input', validate);

        function toggleVisibility(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon = btn.querySelector('i');
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        }
    </script>

@endsection
