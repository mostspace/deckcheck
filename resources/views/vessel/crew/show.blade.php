@extends('layouts.app')

@section('title', 'Crew Profile | '.$user->name )

@section('content')

{{--Header--}}
    <div class="mb-6">
            <div class="flex items-center gap-3 mb-4">
                <button onclick="window.location='{{ route('vessel.crew') }}'" class="p-2 text-[#667084] hover:text-[#344053] hover:bg-white rounded-lg">
                    <i class="fa-solid fa-arrow-left"></i>
                </button>
                <h1 class="text-2xl font-semibold text-[#0f1728]">Crew Member Profile</h1>
            </div>
    </div>

    
{{--Hero Section--}}    
    <div class="bg-white rounded-lg border border-[#e4e7ec] shadow-[0px_1px_2px_0px_rgba(16,24,40,0.05)] mb-6">
            <div class="p-6">
                <div class="flex items-start gap-6">
                    <div class="flex-shrink-0">
                        <img src="{{ $user->profile_pic ? Storage::url($user->profile_pic) : asset('images/placeholders/placeholder.png') }}" alt="Profile picture" class="w-24 h-24 rounded-full border-4 border-white shadow-[0px_4px_6px_-2px_rgba(16,24,40,0.03)]">
                    </div>
                    <div class="flex-1">
                        <div class="flex items-start justify-between">
                            <div>
                                <h2 class="text-xl font-semibold text-[#0f1728] mb-1">{{ $user->first_name }} {{ $user->last_name }}</h2>
                                <p class="text-[#475466] text-base mb-2">{{ $user->department ?? '*In Dev - Department*' }}</p>
                                <p class="text-[#6840c6] font-medium">{{ $user->role ?? '*In Dev - Title*' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    
    
{{--Information Sections--}}    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    
        <!-- Personal Information -->
            <div class="bg-white rounded-lg border border-[#e4e7ec] shadow-[0px_1px_2px_0px_rgba(16,24,40,0.05)]">
                <div class="px-6 py-4 border-b border-[#e4e7ec]">
                    <h3 class="text-lg font-semibold text-[#0f1728]">Personal Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="text-sm font-medium text-[#344053] block mb-1">Join Date</label>
                        <p class="text-[#0f1728]">{{ $user->created_at->format('F j, Y') }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-[#344053] block mb-1">Email Address</label>
                        <p class="text-[#0f1728]">{{ $user->email }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-[#344053] block mb-1">Phone Number</label>
                        <p class="text-[#0f1728]">*In Dev*</p>
                    </div>
                </div>
            </div>

        <!-- Emergency Contact -->
            <div class="bg-white rounded-lg border border-[#e4e7ec] shadow-[0px_1px_2px_0px_rgba(16,24,40,0.05)]">
                <div class="px-6 py-4 border-b border-[#e4e7ec]">
                    <h3 class="text-lg font-semibold text-[#0f1728]">Emergency Contact</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="text-sm font-medium text-[#344053] block mb-1">Contact Name</label>
                        <p class="text-[#0f1728]">*In Dev*</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-[#344053] block mb-1">Relationship</label>
                        <p class="text-[#0f1728]">*In Dev*</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-[#344053] block mb-1">Phone Number</label>
                        <p class="text-[#0f1728]">*In Dev*</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-[#344053] block mb-1">Email Address</label>
                        <p class="text-[#0f1728]">*In Dev*</p>
                    </div>
                </div>
            </div>
    </div>
    

@endsection