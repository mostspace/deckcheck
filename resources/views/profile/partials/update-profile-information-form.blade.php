<section>
    
    {{-- Email Verification Prompt --}}
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    {{-- Profile Picture Update --}}
    <form action="{{ route('profile.picture.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div id="profile-picture-section" class="bg-white rounded-lg shadow-sm border border-[#e4e7ec] p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold">Profile Picture</h2>
                <button type="submit"
                    class="px-4 py-2 bg-[#7e56d8] text-white rounded-lg shadow-[0px_1px_2px_0px_rgba(16,24,40,0.05)] border border-[#7e56d8] text-sm">
                    Save
                </button>
            </div>

            <div class="flex items-center gap-6">
                <div class="w-24 h-24 rounded-full bg-[#f2f3f6] flex items-center justify-center overflow-hidden">
                    <img src="{{ $user->profile_pic ? asset('storage/' . $user->profile_pic) : 'https://via.placeholder.com/300' }}" alt="Profile"
                        class="w-full h-full object-cover">
                </div>

                <div class="flex flex-col gap-2">
                    <div class="flex gap-3">
                        <input type="file" name="profile_pic" id="profile_pic" class="hidden" onchange="this.form.submit()">
                        <label for="profile_pic" class="cursor-pointer px-4 py-2 bg-[#7e56d8] text-white rounded-lg border border-[#7e56d8]">
                            Upload New
                        </label>

                        <button type="button" class="px-4 py-2 bg-white text-[#344053] rounded-lg border border-[#cfd4dc]"
                            onclick="document.getElementById('profile_pic').value = ''; alert('Remove logic not implemented yet.')">
                            Remove
                        </button>
                    </div>
                    <p class="text-sm text-[#475466]">Recommended: Square image, at least 300x300px</p>
                </div>
            </div>
        </div>
    </form>

    {{-- Personal Info Update --}}
   <form action="{{ route('profile.info.update') }}" method="POST">
    @csrf
    @method('PUT')

    <div id="personal-info-section" class="bg-white rounded-lg shadow-sm border border-[#e4e7ec] p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Personal Information</h2>
            <button type="submit" class="px-4 py-2 bg-[#7e56d8] text-white rounded-lg border border-[#7e56d8] text-sm">
                Save
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- #First Name --}}
            <div class="flex-col gap-1.5 flex">
                <label for="first_name" class="text-[#344053] text-sm font-medium">First Name</label>
                <div class="px-3.5 py-2.5 bg-white rounded-lg border border-[#cfd4dc] flex">
                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $user->first_name) }}"
                        class="grow text-[#0f1728] text-base outline-none">
                </div>
            </div>

            {{-- #Last Name --}}
            <div class="flex-col gap-1.5 flex">
                <label for="last_name" class="text-[#344053] text-sm font-medium">Last Name</label>
                <div class="px-3.5 py-2.5 bg-white rounded-lg border border-[#cfd4dc] flex">
                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $user->last_name) }}"
                        class="grow text-[#0f1728] text-base outline-none">
                </div>
            </div>

            {{-- #Email --}}
            <div class="flex-col gap-1.5 flex">
                <label for="email" class="text-[#344053] text-sm font-medium">Email</label>
                <div class="px-3.5 py-2.5 bg-white rounded-lg border border-[#cfd4dc] flex">
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                        class="grow text-[#0f1728] text-base outline-none">
                </div>
            </div>

            {{-- #Phone --}}
            <div class="flex-col gap-1.5 flex">
                <label for="phone" class="text-[#344053] text-sm font-medium">Phone Number</label>
                <div class="px-3.5 py-2.5 bg-white rounded-lg border border-[#cfd4dc] flex">
                    <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                        class="grow text-[#0f1728] text-base outline-none">
                </div>
            </div>

            {{-- #Date of Birth --}}
            <div class="flex-col gap-1.5 flex">
                <label for="date_of_birth" class="text-[#344053] text-sm font-medium">Date of Birth</label>
                <div class="px-3.5 py-2.5 bg-white rounded-lg border border-[#cfd4dc] flex">
                    <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth?->format('Y-m-d')) }}"
                        class="grow text-[#0f1728] text-base outline-none">
                </div>
            </div>

            {{-- #Nationality --}}
            <div class="flex-col gap-1.5 flex">
                <label for="nationality" class="text-[#344053] text-sm font-medium">Nationality</label>
                <div class="px-3.5 py-2.5 bg-white rounded-lg border border-[#cfd4dc] flex">
                    <select name="nationality" id="nationality" class="grow text-[#0f1728] text-base outline-none bg-transparent">
                        @foreach (['US' => 'United States', 'GB' => 'United Kingdom', 'CA' => 'Canada', 'AU' => 'Australia', 'NZ' => 'New Zealand'] as $code => $label)
                            <option value="{{ $code }}" {{ old('nationality', $user->nationality) === $code ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</form>


{{--

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-stock.input-label for="name" :value="__('Name')" />
            <x-stock.text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus
                autocomplete="name" />
            <x-stock.input-error class="mt-2" :messages="$errors->get('name')" />
        </div>
        <div>
            <x-stock.input-label for="first_name" :value="__('First Name')" />
            <x-stock.text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full" :value="old('first_name', $user->first_name)" required autofocus
                autocomplete="first_name" />
            <x-stock.input-error class="mt-2" :messages="$errors->get('first_name')" />
        </div>
        <div>
            <x-stock.input-label for="last_name" :value="__('Last Name')" />
            <x-stock.text-input id="last_name" name="last_name" type="text" class="mt-1 block w-full" :value="old('last_name', $user->last_name)" required autofocus
                autocomplete="last_name" />
            <x-stock.input-error class="mt-2" :messages="$errors->get('last_name')" />
        </div>
        <div class="md:col-span-2">
            <label for="profile_pic" class="block text-sm font-medium text-gray-700">
                Change Photo
            </label>
            <input id="profile_pic" name="profile_pic" type="file" accept="image/*"
                class="block w-full text-gray-70 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
        </div>




        <div>
            <x-stock.input-label for="email" :value="__('Email')" />
            <x-stock.text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required
                autocomplete="username" />
            <x-stock.input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification"
                            class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-stock.primary-button>{{ __('Save') }}</x-stock.primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form> --}}
</section>
