@extends('v2.layouts.app')

@section('title', 'Create Interval')

@section('content')

    @include('v2.components.nav.header-routing', [
        'activeTab' => 'index',
        'context' => 'maintenance',
        'breadcrumbs' => [
            [
                'label' => 'Maintenance',
                'icon' => asset('assets/media/icons/sidebar-solid-wrench-scredriver.svg'),
                'url' => route('maintenance.index')
            ],
            [
                'label' => $category->name,
                'url' => route('maintenance.show', $category)
            ],
            ['label' => 'Create Interval', 'active' => true]
        ],
        'actions' => [
            [
                'type' => 'link',
                'label' => 'Back to Category',
                'url' => route('maintenance.show', $category),
                'icon' => 'fas fa-arrow-left',
                'class' => 'bg-slate-500 text-white hover:bg-slate-600'
            ]
        ]
    ])

    <div class="px-3 py-4 sm:px-6 sm:py-6 lg:px-8">
        {{-- System Messages --}}
        @if (session('success'))
            <div class="mb-6 rounded-lg border border-green-300 bg-green-100 p-4 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 rounded-lg border border-red-300 bg-red-100 p-4 text-sm text-red-800">
                {{ session('error') }}
            </div>
        @endif

        {{-- Form Container --}}
        <div class="mx-auto max-w-2xl">
            <div class="rounded-lg border border-[#e4e7ec] bg-white shadow-sm">
                {{-- Header --}}
                <div class="border-b border-[#e4e7ec] px-6 py-4">
                    <h1 class="text-xl font-semibold text-[#0f1728]">New Interval</h1>
                    <p class="mt-1 text-sm text-[#475466]">Define a new interval under <span
                            class="font-medium text-[#6840c6]">{{ $category->name }}</span></p>
                </div>

                {{-- Form --}}
                <form action="{{ route('maintenance.intervals.store', $category) }}" method="POST" class="space-y-6 p-6">
                    @csrf

                    {{-- Description --}}
                    <div>
                        <label for="description" class="mb-2 block text-sm font-medium text-[#344053]">Description</label>
                        <textarea name="description" id="description" rows="4"
                            class="@error('description') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror w-full resize-none rounded-lg border border-[#cfd4dc] bg-white px-3.5 py-2.5 text-base text-[#0f1728] focus:border-[#6840c6] focus:outline-none focus:ring-2 focus:ring-[#6840c6]"
                            placeholder="Enter description...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Interval --}}
                    <div>
                        <label for="interval" class="mb-2 block text-sm font-medium text-[#344053]">Interval</label>
                        <select name="interval" id="interval"
                            class="@error('interval') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror w-full appearance-none rounded-lg border border-[#cfd4dc] bg-white px-3.5 py-2.5 pr-10 text-base text-[#0f1728] focus:border-[#6840c6] focus:outline-none focus:ring-2 focus:ring-[#6840c6]">
                            <option value="">Select Interval...</option>
                            @foreach (['Daily', 'Bi-Weekly', 'Weekly', 'Monthly', 'Quarterly', 'Bi-Annually', 'Annual', '2-Yearly', '3-Yearly', '5-Yearly', '6-Yearly', '10-Yearly', '12-Yearly'] as $freq)
                                <option value="{{ $freq }}" {{ old('interval') == $freq ? 'selected' : '' }}>
                                    {{ $freq }}</option>
                            @endforeach
                        </select>
                        @error('interval')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Facilitator --}}
                    <div>
                        <label for="facilitator" class="mb-2 block text-sm font-medium text-[#344053]">Facilitator</label>
                        <select name="facilitator" id="facilitator"
                            class="@error('facilitator') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror w-full appearance-none rounded-lg border border-[#cfd4dc] bg-white px-3.5 py-2.5 pr-10 text-base text-[#0f1728] focus:border-[#6840c6] focus:outline-none focus:ring-2 focus:ring-[#6840c6]">
                            <option value="">Select Facilitator...</option>
                            @foreach (['Crew', 'Service Provider'] as $fac)
                                <option value="{{ $fac }}" {{ old('facilitator') == $fac ? 'selected' : '' }}>
                                    {{ $fac }}</option>
                            @endforeach
                        </select>
                        @error('facilitator')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Buttons --}}
                    <div class="flex justify-end space-x-4 border-t border-[#e4e7ec] pt-6">
                        <a href="{{ route('maintenance.show', $category) }}"
                            class="rounded-lg border border-[#cfd4dc] bg-white px-4 py-2.5 text-sm font-medium text-[#344053] transition-colors hover:bg-[#f9fafb]">
                            Cancel
                        </a>

                        <button type="submit"
                            class="rounded-lg border border-[#7e56d8] bg-[#7e56d8] px-4 py-2.5 text-sm font-medium text-white shadow transition-colors hover:bg-[#6840c6]">
                            <i class="fa-solid fa-plus mr-2"></i>
                            Save Interval
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
