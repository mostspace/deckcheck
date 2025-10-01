@extends('layouts.app')

@section('title', 'Profile Settings')

@section('content')

    {{-- Navigation --}}
    <div id="navigation-section" class="p-6">
        <div class="container mx-auto flex items-center justify-between">
            <span class="flex cursor-pointer items-center text-[#344053] transition-colors hover:text-[#6840c6]">
                <a href="{{ route('dashboard') }}"><i class="fa-solid fa-arrow-left mr-2"></i>
                    <span>Back to Dashboard</span></a>
            </span>
            <button class="flex cursor-pointer items-center text-[#d92d20] transition-colors hover:text-[#b42318]">
                <i class="fa-solid fa-sign-out-alt mr-2"></i>
                <span>Logout</span>
            </button>
        </div>
    </div>

    <div id="profile-settings" class="container mx-auto px-6 pb-8">
        <div class="mx-auto max-w-4xl">
            <h1 class="mb-8 text-2xl font-semibold text-[#0f1728]">Profile Settings</h1>

            {{-- Profile Picture & Personal Information --}}
            <div class="space-y-8">
                @include('profile.partials.update-profile-information-form')
            </div>

            {{-- Update Password --}}
            <div class="mb-6 bg-white p-4 shadow sm:rounded-lg sm:p-8 dark:bg-gray-800">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Delete Account --}}
            <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8 dark:bg-gray-800">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>

@endsection
