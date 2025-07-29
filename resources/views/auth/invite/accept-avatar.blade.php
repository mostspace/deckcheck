@extends('layouts.guest')

@section('title', 'Welcome | Step 2: Profile Picture')

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
                                2/3
                            </div>
                        </div>
                        <h2 class="text-2xl font-bold text-[#0f1728]">Profile Picture</h2>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <div class="p-6">

                <form method="POST" action="{{ route('invitations.accept.avatar.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <!-- Profile Photo Upload -->
                    <div class="flex-col justify-start items-start gap-1.5 flex" id="profile-photo-field">
                        <label class="text-[#344053] text-sm font-medium">Profile Photo</label>
                        <div id="dropzone"
                            class="w-full p-6 bg-white rounded-lg border border-[#e4e7ec] border-dashed flex flex-col items-center justify-center gap-4 cursor-pointer">
                            <div id="avatarPreview" class="w-24 h-24 rounded-full bg-[#f2f3f6] overflow-hidden flex items-center justify-center">
                                <i class="fa-regular fa-user text-[#667084] text-4xl"></i>
                            </div>
                            <div class="text-center">
                                <p class="text-[#344053] font-medium mb-1">
                                    <span class="text-[#6840c6]">Click to upload</span> or drag and drop
                                </p>
                                <p class="text-[#475466] text-sm">SVG, PNG, JPG or GIF (max. 800x800px)</p>
                            </div>
                            <input type="file" id="profile-photo" name="avatar" class="hidden" accept="image/*" required>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="p-6 bg-white border-t border-[#e4e7ec] flex justify-between" id="form-actions">
                        <a href="{{ route('invitations.accept', ['token' => $token]) }}">
                            <div class="px-[18px] py-2.5 bg-white rounded-lg border border-[#cfd4dc] flex items-center gap-2">
                                <i class="fa-solid fa-arrow-left text-[#344053]"></i>
                                <span class="text-[#344053] text-base font-medium">Back</span>
                            </div>
                        </a>

                        <button type="submit" id="submitButton"
                            class="px-[18px] py-2.5 bg-[#7e56d8] rounded-lg border border-[#7e56d8] text-white font-medium flex items-center gap-2">
                            Complete Profile
                            <i class="fa-solid fa-check text-white"></i>
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

        fileInput.addEventListener('change', () => {
            if (fileInput.files[0]) {
                previewImage(fileInput.files[0]);
            }
        });

        function previewImage(file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                avatarPreview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover rounded-full">`;
            };
            reader.readAsDataURL(file);
        }
    </script>


@endsection
