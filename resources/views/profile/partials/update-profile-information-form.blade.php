<section>

    {{-- Email Verification Prompt --}}
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    {{-- Profile Picture Update --}}
    <form action="{{ route('profile.picture.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div id="profile-picture-section" class="mb-6 rounded-lg border border-[#e4e7ec] bg-white p-6 shadow-sm">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-semibold">Profile Picture</h2>
                <button type="submit"
                    class="rounded-lg border border-[#7e56d8] bg-[#7e56d8] px-4 py-2 text-sm text-white shadow-[0px_1px_2px_0px_rgba(16,24,40,0.05)]">
                    Save
                </button>
            </div>

            <div class="flex items-center gap-6">
                <div class="flex h-24 w-24 items-center justify-center overflow-hidden rounded-full bg-[#f2f3f6]">
                    <img src="{{ $user->profile_pic ? Storage::url($user->profile_pic) : asset('images/placeholders/placeholder.png') }}"
                        alt="Profile" class="h-full w-full object-cover">
                </div>

                <div class="flex flex-col gap-2">
                    <div class="flex gap-3">
                        <input type="file" name="profile_pic" id="profile_pic" class="hidden"
                            onchange="this.form.submit()">
                        <label for="profile_pic"
                            class="cursor-pointer rounded-lg border border-[#7e56d8] bg-[#7e56d8] px-4 py-2 text-white">
                            Upload New
                        </label>

                        <button type="button"
                            class="rounded-lg border border-[#cfd4dc] bg-white px-4 py-2 text-[#344053]"
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

        <div id="personal-info-section" class="mb-6 rounded-lg border border-[#e4e7ec] bg-white p-6 shadow-sm">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-semibold">Personal Information</h2>
                <button type="submit"
                    class="rounded-lg border border-[#7e56d8] bg-[#7e56d8] px-4 py-2 text-sm text-white">
                    Save
                </button>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                {{-- #First Name --}}
                <div class="flex flex-col gap-1.5">
                    <label for="first_name" class="text-sm font-medium text-[#344053]">First Name</label>
                    <div class="flex rounded-lg border border-[#cfd4dc] bg-white px-3.5 py-2.5">
                        <input type="text" name="first_name" id="first_name"
                            value="{{ old('first_name', $user->first_name) }}"
                            class="grow text-base text-[#0f1728] outline-none">
                    </div>
                </div>

                {{-- #Last Name --}}
                <div class="flex flex-col gap-1.5">
                    <label for="last_name" class="text-sm font-medium text-[#344053]">Last Name</label>
                    <div class="flex rounded-lg border border-[#cfd4dc] bg-white px-3.5 py-2.5">
                        <input type="text" name="last_name" id="last_name"
                            value="{{ old('last_name', $user->last_name) }}"
                            class="grow text-base text-[#0f1728] outline-none">
                    </div>
                </div>

                {{-- #Email --}}
                <div class="flex flex-col gap-1.5">
                    <label for="email" class="text-sm font-medium text-[#344053]">Email</label>
                    <div class="flex rounded-lg border border-[#cfd4dc] bg-white px-3.5 py-2.5">
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                            class="grow text-base text-[#0f1728] outline-none">
                    </div>
                </div>

                {{-- #Phone --}}
                <div class="flex flex-col gap-1.5">
                    <label for="phone" class="text-sm font-medium text-[#344053]">Phone Number</label>
                    <div class="flex rounded-lg border border-[#cfd4dc] bg-white px-3.5 py-2.5">
                        <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                            class="grow text-base text-[#0f1728] outline-none">
                    </div>
                </div>

                {{-- #Date of Birth --}}
                <div class="flex flex-col gap-1.5">
                    <label for="date_of_birth" class="text-sm font-medium text-[#344053]">Date of Birth</label>
                    <div class="flex rounded-lg border border-[#cfd4dc] bg-white px-3.5 py-2.5">
                        <input type="date" name="date_of_birth" id="date_of_birth"
                            value="{{ old('date_of_birth', $user->date_of_birth?->format('Y-m-d')) }}"
                            class="grow text-base text-[#0f1728] outline-none">
                    </div>
                </div>

                {{-- #Nationality --}}
                <div class="flex flex-col gap-1.5">
                    <label for="nationality" class="text-sm font-medium text-[#344053]">Nationality</label>
                    <div class="flex rounded-lg border border-[#cfd4dc] bg-white px-3.5 py-2.5">
                        <select name="nationality" id="nationality"
                            class="grow bg-transparent text-base text-[#0f1728] outline-none">
                            @foreach (['US' => 'United States', 'GB' => 'United Kingdom', 'CA' => 'Canada', 'AU' => 'Australia', 'NZ' => 'New Zealand'] as $code => $label)
                                <option value="{{ $code }}"
                                    {{ old('nationality', $user->nationality) === $code ? 'selected' : '' }}>
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
