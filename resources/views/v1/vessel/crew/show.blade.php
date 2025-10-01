@extends('layouts.app')

@section('title', 'Crew Profile | ' . $user->name)

@section('content')

    {{-- Header --}}
    <div class="mb-6">
        <div class="mb-4 flex items-center gap-3">
            <button onclick="window.location='{{ route('vessel.crew') }}'"
                class="rounded-lg p-2 text-[#667084] hover:bg-white hover:text-[#344053]">
                <i class="fa-solid fa-arrow-left"></i>
            </button>
            <h1 class="text-2xl font-semibold text-[#0f1728]">Crew Member Profile</h1>
        </div>
    </div>

    {{-- Hero Section --}}
    <div class="mb-6 rounded-lg border border-[#e4e7ec] bg-white shadow-[0px_1px_2px_0px_rgba(16,24,40,0.05)]">
        <div class="p-6">
            <div class="flex items-start gap-6">
                <div class="flex-shrink-0">
                    <img src="{{ $user->profile_pic ? Storage::url($user->profile_pic) : asset('images/placeholders/user.png') }}"
                        alt="Profile picture"
                        class="h-24 w-24 rounded-full border-4 border-white shadow-[0px_4px_6px_-2px_rgba(16,24,40,0.03)]">
                </div>
                <div class="flex-1">
                    <div class="flex items-start justify-between">
                        <div>
                            <h2 class="mb-1 text-xl font-semibold text-[#0f1728]">{{ $user->first_name }}
                                {{ $user->last_name }}</h2>
                            <p class="mb-2 text-base text-[#475466]">{{ $user->department ?? '*In Dev - Department*' }}</p>
                            <p class="font-medium text-[#6840c6]">{{ $user->role ?? '*In Dev - Title*' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Information Sections --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

        <!-- Personal Information -->
        <div class="rounded-lg border border-[#e4e7ec] bg-white shadow-[0px_1px_2px_0px_rgba(16,24,40,0.05)]">
            <div class="border-b border-[#e4e7ec] px-6 py-4">
                <h3 class="text-lg font-semibold text-[#0f1728]">Personal Information</h3>
            </div>
            <div class="space-y-4 p-6">
                <div>
                    <label class="mb-1 block text-sm font-medium text-[#344053]">Join Date</label>
                    <p class="text-[#0f1728]">{{ $user->created_at->format('F j, Y') }}</p>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-[#344053]">Email Address</label>
                    <p class="text-[#0f1728]">{{ $user->email }}</p>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-[#344053]">Phone Number</label>
                    <p class="text-[#0f1728]">*In Dev*</p>
                </div>
            </div>
        </div>

        <!-- Emergency Contact -->
        <div class="rounded-lg border border-[#e4e7ec] bg-white shadow-[0px_1px_2px_0px_rgba(16,24,40,0.05)]">
            <div class="border-b border-[#e4e7ec] px-6 py-4">
                <h3 class="text-lg font-semibold text-[#0f1728]">Emergency Contact</h3>
            </div>
            <div class="space-y-4 p-6">
                <div>
                    <label class="mb-1 block text-sm font-medium text-[#344053]">Contact Name</label>
                    <p class="text-[#0f1728]">*In Dev*</p>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-[#344053]">Relationship</label>
                    <p class="text-[#0f1728]">*In Dev*</p>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-[#344053]">Phone Number</label>
                    <p class="text-[#0f1728]">*In Dev*</p>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-[#344053]">Email Address</label>
                    <p class="text-[#0f1728]">*In Dev*</p>
                </div>
            </div>
        </div>
    </div>

@endsection
