@extends('layouts.guest')

@section('title', 'Welcome | Step 3: Terms & Conditions')

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
                                3/3
                            </div>
                        </div>
                        <h2 class="text-2xl font-bold text-[#0f1728]">Terms & Conditions</h2>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <div class="p-6">
                <form method="POST" action="{{ route('invitations.accept.terms.store') }}" class="space-y-6">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <!-- Scrollable Terms -->
                    <div class="flex flex-col gap-2">
                        <label for="terms-scroll" class="text-[#344053] text-sm font-medium">Please review our Terms & Conditions</label>
                        <div id="terms-scroll" class="h-64 overflow-y-scroll border border-[#e4e7ec] rounded p-4 text-sm text-[#475466] bg-[#f9fafb]">
                            {{-- Replace with your actual terms --}}
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor...</p>
                            <p>Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat...</p>
                            <p>Donec sit amet eros. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Mauris fermentum dictum magna...</p>
                            <p>All users must agree to these terms in order to access the platform.</p>
                            <p>Thank you for joining us.</p>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor...</p>
                            <p>Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat...</p>
                            <p>Donec sit amet eros. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Mauris fermentum dictum magna...</p>
                            <p>All users must agree to these terms in order to access the platform.</p>
                            <p>Thank you for joining us.</p>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor...</p>
                            <p>Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat...</p>
                            <p>Donec sit amet eros. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Mauris fermentum dictum magna...</p>
                            <p>All users must agree to these terms in order to access the platform.</p>
                            <p>Thank you for joining us.</p>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor...</p>
                            <p>Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat...</p>
                            <p>Donec sit amet eros. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Mauris fermentum dictum magna...</p>
                            <p>All users must agree to these terms in order to access the platform.</p>
                            <p>Thank you for joining us.</p>
                        </div>
                    </div>

                    <!-- Agreement Checkbox -->
                    <div class="flex items-start space-x-2">
                        <input id="accept" type="checkbox" name="accept" disabled class="mt-1" />
                        <label for="accept" class="text-sm text-[#344053]">I have read and agree to the Terms & Conditions</label>
                    </div>

                    <!-- Form Actions -->
                    <div class="p-6 bg-white border-t border-[#e4e7ec] flex justify-between" id="form-actions">
                        <a href="{{ route('invitations.accept.avatar', ['token' => $token]) }}">
                            <div class="px-[18px] py-2.5 bg-white rounded-lg border border-[#cfd4dc] flex items-center gap-2">
                                <i class="fa-solid fa-arrow-left text-[#344053]"></i>
                                <span class="text-[#344053] text-base font-medium">Back</span>
                            </div>
                        </a>

                        <button type="submit" id="submitButton" disabled
                            class="px-[18px] py-2.5 bg-[#7e56d8] rounded-lg border border-[#7e56d8] text-white font-medium flex items-center gap-2 opacity-50 cursor-not-allowed">
                            Complete Setup
                            <i class="fa-solid fa-check text-white"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scroll Detection Script -->
    <script>
        const termsScroll = document.getElementById('terms-scroll');
        const checkbox = document.getElementById('accept');
        const submitButton = document.getElementById('submitButton');

        termsScroll.addEventListener('scroll', () => {
            const isScrolledToBottom = termsScroll.scrollTop + termsScroll.clientHeight >= termsScroll.scrollHeight - 10;

            if (isScrolledToBottom) {
                checkbox.disabled = false;
            }
        });

        checkbox.addEventListener('change', () => {
            if (checkbox.checked) {
                submitButton.disabled = false;
                submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
            } else {
                submitButton.disabled = true;
                submitButton.classList.add('opacity-50', 'cursor-not-allowed');
            }
        });
    </script>

@endsection
