@extends('layouts.app')

@section('title', 'Profile Settings')

@section('content')

    {{-- Navigation --}}
    <div id="navigation-section" class="p-6">
        <div class="container mx-auto flex justify-between items-center">
            <span class="flex items-center text-[#344053] hover:text-[#6840c6] transition-colors cursor-pointer">
                <a href="{{ route('dashboard') }}"><i class="fa-solid fa-arrow-left mr-2"></i>
                <span>Back to Dashboard</span></a>
            </span>
            <button class="flex items-center text-[#d92d20] hover:text-[#b42318] transition-colors cursor-pointer">
                <i class="fa-solid fa-sign-out-alt mr-2"></i>
                <span>Logout</span>
            </button>
        </div>
    </div>

    <div id="profile-settings" class="container mx-auto px-6 pb-8">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-2xl font-semibold text-[#0f1728] mb-8">Profile Settings</h1>

            {{-- Profile Picture & Personal Information --}}
            <div class="space-y-8">
                @include('profile.partials.update-profile-information-form')
            </div>

            {{-- Update Password --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg mb-6">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Delete Account --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>

@endsection
