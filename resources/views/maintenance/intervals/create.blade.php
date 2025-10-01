@extends('layouts.form-page')

@php
    $title = 'New Interval';
    $subtitle = "Define a new interval under '{$category->name}'";
@endphp

@section('form')
    <form action="{{ route('maintenance.intervals.store', $category) }}" method="POST" class="space-y-6">
        @csrf

        {{-- Description --}}
        <div>
            <label for="description" class="mb-2 block text-sm font-medium text-[#344053]">Description</label>
            <input name="description" id="description" rows="4"
                class="w-full resize-none rounded-lg border border-[#cfd4dc] bg-white px-3.5 py-2.5 text-base text-[#0f1728] focus:outline-none focus:ring-2 focus:ring-[#6840c6]"
                placeholder="Enter description...">
        </div>

        {{-- Frequency --}}
        <div>
            <label for="interval" class="mb-2 block text-sm font-medium text-[#344053]">Interval</label>
            <select name="interval" id="interval"
                class="w-full appearance-none rounded-lg border border-[#cfd4dc] bg-white px-3.5 py-2.5 pr-10 text-base text-[#0f1728] focus:outline-none focus:ring-2 focus:ring-[#6840c6]">
                <option value="">Select Interval...</option>
                @foreach (['Daily', 'Bi-Weekly', 'Weekly', 'Monthly', 'Quarterly', 'Bi-Annually', 'Annual', '2-Yearly', '3-Yearly', '5-Yearly', '6-Yearly', '10-Yearly', '12-Yearly'] as $freq)
                    <option value="{{ $freq }}" {{ old('interval') == $freq ? 'selected' : '' }}>{{ $freq }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Facilitator --}}
        <div>
            <label for="facilitator" class="mb-2 block text-sm font-medium text-[#344053]">Facilitator</label>
            <select name="facilitator" id="facilitator"
                class="w-full appearance-none rounded-lg border border-[#cfd4dc] bg-white px-3.5 py-2.5 pr-10 text-base text-[#0f1728] focus:outline-none focus:ring-2 focus:ring-[#6840c6]">
                <option value="">Select Facilitator...</option>
                @foreach (['Crew', 'Service Provider'] as $fac)
                    <option value="{{ $fac }}" {{ old('facilitator') == $fac ? 'selected' : '' }}>
                        {{ $fac }}</option>
                @endforeach
            </select>
        </div>

        {{-- Buttons --}}
        <div class="flex justify-end space-x-4">
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
@endsection
