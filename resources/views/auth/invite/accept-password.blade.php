@extends('layouts.guest')

@section('title', 'Welcome | Step 1: Personal Information')

@section('content')

    <!-- Fullscreen Centered Container -->
    <div class="min-h-screen flex items-center justify-center bg-gray-50 px-4">
        <!-- Registration Container -->
        <div class="w-full max-w-3xl bg-white rounded-xl shadow-lg overflow-hidden">

            <!-- Progress Indicator -->
            <div class="bg-[#f8f9fb] p-6 border-b border-[#e4e7ec]">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="relative w-12 h-12">
                            <div class="w-12 h-12 rounded-full border-4 border-[#7e56d8] border-r-transparent animate-spin"></div>
                            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-sm font-medium">
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
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex-col justify-start items-start gap-1.5 flex">
                            <label for="first_name" class="text-[#344053] text-sm font-medium">First Name</label>
                            <div class="self-stretch px-3.5 py-2.5 bg-[#f9fafb] rounded-lg shadow border border-[#cfd4dc] flex items-center gap-2">
                                <input id="first_name" name="first_name" type="text" value="{{ $invitation->boarding->user->first_name }}"
                                    class="grow text-[#98a2b3] text-base outline-none bg-transparent" readonly disabled>
                            </div>
                        </div>
                        <div class="flex-col justify-start items-start gap-1.5 flex">
                            <label for="last_name" class="text-[#344053] text-sm font-medium">Last Name</label>
                            <div class="self-stretch px-3.5 py-2.5 bg-[#f9fafb] rounded-lg shadow border border-[#cfd4dc] flex items-center gap-2">
                                <input id="last_name" name="last_name" type="text" value="{{ $invitation->boarding->user->last_name }}"
                                    class="grow text-[#98a2b3] text-base outline-none bg-transparent" readonly disabled>
                            </div>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="flex-col justify-start items-start gap-1.5 flex">
                        <label for="email" class="text-[#344053] text-sm font-medium">Email</label>
                        <div class="self-stretch px-3.5 py-2.5 bg-[#f9fafb] rounded-lg shadow border border-[#cfd4dc] flex items-center gap-2">
                            <input id="email" name="email" type="email" value="{{ $invitation->boarding->user->email }}"
                                class="grow text-[#98a2b3] text-base outline-none bg-transparent" readonly disabled>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="flex-col justify-start items-start gap-1.5 flex">
                        <label for="phone" class="text-[#344053] text-sm font-medium">Phone Number</label>
                        <div class="self-stretch px-3.5 py-2.5 bg-[#f9fafb] rounded-lg shadow border border-[#cfd4dc] flex items-center gap-2">
                            <input id="phone" name="phone" type="tel" value="{{ $invitation->boarding->user->phone }}"
                                class="grow text-[#98a2b3] text-base outline-none bg-transparent" readonly disabled>
                        </div>
                    </div>

                    <!-- Password Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex-col justify-start items-start gap-1.5 flex">
                            <label for="password" class="text-[#344053] text-sm font-medium">Password</label>
                            <div class="self-stretch px-3.5 py-2.5 bg-white rounded-lg shadow border border-[#cfd4dc] flex items-center gap-2">
                                <input id="password" name="password" type="password" class="grow text-[#0f1728] text-base outline-none bg-transparent"
                                    placeholder="Create a password" required>
                                <button type="button" class="text-[#667084]" onclick="toggleVisibility('password', this)">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="flex-col justify-start items-start gap-1.5 flex">
                            <label for="password_confirmation" class="text-[#344053] text-sm font-medium">Confirm Password</label>
                            <div class="self-stretch px-3.5 py-2.5 bg-white rounded-lg shadow border border-[#cfd4dc] flex items-center gap-2">
                                <input id="password_confirmation" name="password_confirmation" type="password"
                                    class="grow text-[#0f1728] text-base outline-none bg-transparent" placeholder="Confirm your password" required>
                                <button type="button" class="text-[#667084]" onclick="toggleVisibility('password_confirmation', this)">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Password Requirements -->
                    <div class="p-4 bg-[#f8f9fb] rounded-lg border border-[#e4e7ec]">
                        <div class="text-[#344053] text-sm font-medium mb-2">Password Requirements:</div>
                        <ul id="passwordRules" class="space-y-2 text-sm text-[#98a2b3]">
                            <li class="flex items-center rule" data-rule="length">
                                <i class="fa-solid fa-check mr-2"></i> At least 8 characters long
                            </li>
                            <li class="flex items-center rule" data-rule="uppercase">
                                <i class="fa-solid fa-check mr-2"></i> Contains at least one uppercase letter
                            </li>
                            <li class="flex items-center rule" data-rule="number">
                                <i class="fa-solid fa-check mr-2"></i> Contains at least one number
                            </li>
                            <li class="flex items-center rule" data-rule="match">
                                <i class="fa-solid fa-check mr-2"></i> Passwords match
                            </li>
                        </ul>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end pt-6 border-t border-[#e4e7ec]">
                        <button type="submit" id="submitButton"
                            class="px-[18px] py-2.5 bg-[#7e56d8] rounded-lg shadow border border-[#7e56d8] text-white font-medium flex items-center gap-2 disabled:opacity-50"
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
