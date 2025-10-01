@extends('layouts.guest')

@section('title', 'Welcome | Step 2: Profile Picture')

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
                                2/3
                            </div>
                        </div>
                        <h2 class="text-2xl font-bold text-[#0f1728]">Profile Picture</h2>
                    </div>
                </div>
            </div>

            {{--   @include('components.registration.inviting-vessel-summary') --}}

            <!-- Form Content -->
            <div class="p-6">

                <form method="POST" action="{{ route('invitations.accept.avatar.store') }}" enctype="multipart/form-data"
                    class="space-y-6">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <!-- Profile Photo Upload -->
                    <div class="flex flex-col items-start justify-start gap-1.5" id="profile-photo-field">
                        <label class="text-sm font-medium text-[#344053]">Profile Photo</label>
                        <div id="dropzone"
                            class="flex w-full cursor-pointer flex-col items-center justify-center gap-4 rounded-lg border border-dashed border-[#e4e7ec] bg-white p-6">
                            <div id="avatarPreview"
                                class="flex h-24 w-24 items-center justify-center overflow-hidden rounded-full bg-[#f2f3f6]">
                                <i class="fa-regular fa-user text-4xl text-[#667084]"></i>
                            </div>
                            <div class="text-center">
                                <p class="mb-1 font-medium text-[#344053]">
                                    <span class="text-[#6840c6]">Click to upload</span> or drag and drop
                                </p>
                                <p class="text-sm text-[#475466]">SVG, PNG, JPG or GIF (max. 800x800px)</p>
                            </div>
                            <input type="file" id="profile-photo" name="avatar" class="hidden" accept="image/*"
                                required>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-between border-t border-[#e4e7ec] bg-white p-6" id="form-actions">
                        <a href="{{ route('invitations.accept', ['token' => $token]) }}">
                            <div
                                class="flex items-center gap-2 rounded-lg border border-[#cfd4dc] bg-white px-[18px] py-2.5">
                                <i class="fa-solid fa-arrow-left text-[#344053]"></i>
                                <span class="text-base font-medium text-[#344053]">Back</span>
                            </div>
                        </a>

                        <button type="submit" id="submitButton" disabled
                            class="flex cursor-not-allowed items-center gap-2 rounded-lg border border-[#7e56d8] bg-[#7e56d8] px-[18px] py-2.5 font-medium text-white opacity-50">
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
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('profile-photo');
        const avatarPreview = document.getElementById('avatarPreview');

        dropzone.addEventListener('click', () => fileInput.click());

        dropzone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropzone.classList.add('border-purple-500');
        });

        dropzone.addEventListener('dragleave', () => {
            dropzone.classList.remove('border-purple-500');
        });

        dropzone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropzone.classList.remove('border-purple-500');
            if (e.dataTransfer.files.length > 0) {
                fileInput.files = e.dataTransfer.files;
                previewImage(fileInput.files[0]);
            }
        });

        const submitButton = document.getElementById('submitButton');

        fileInput.addEventListener('change', () => {
            if (fileInput.files[0]) {
                previewImage(fileInput.files[0]);
                enableSubmit();
            } else {
                disableSubmit();
            }
        });

        function enableSubmit() {
            submitButton.disabled = false;
            submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
        }

        function disableSubmit() {
            submitButton.disabled = true;
            submitButton.classList.add('opacity-50', 'cursor-not-allowed');
        }


        function previewImage(file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                avatarPreview.innerHTML =
                    `<img src="${e.target.result}" class="w-full h-full object-cover rounded-full">`;
            };
            reader.readAsDataURL(file);
        }
    </script>

@endsection
