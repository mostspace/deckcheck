@extends('layouts.form-page')

@php
    $title = 'Edit Interval';
    $subtitle = "Edit interval under '{$category->name}'";
@endphp

@section('form')
    <form action="{{ route('maintenance.intervals.update', [$category, $interval]) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Description --}}
        <div>
            <label for="description" class="block text-sm font-medium text-[#344053] mb-2">Description</label>
            <input name="description" id="description"  value="{{ old('description', $interval->description) }}" rows="4"
                class="w-full px-3.5 py-2.5 bg-white rounded-lg border border-[#cfd4dc] text-[#0f1728] text-base focus:outline-none focus:ring-2 focus:ring-[#6840c6] resize-none"
                placeholder="{{ old('description', $interval->description) }}">
        </div>
        
        {{-- Interval --}}
        <div>
            <label for="interval" class="block text-sm font-medium text-[#344053] mb-2">Interval</label>
            <select name="interval" id="interval"
                class="w-full px-3.5 py-2.5 bg-white rounded-lg border border-[#cfd4dc] text-[#0f1728] text-base focus:outline-none focus:ring-2 focus:ring-[#6840c6] pr-10 appearance-none">
                @foreach (['Daily', 'Bi-Weekly', 'Weekly', 'Monthly', 'Quarterly', 'Bi-Annually', 'Annual', '2-Yearly', '3-Yearly', '5-Yearly', '6-Yearly', '10-Yearly', '12-Yearly'] as $freq)
                    <option value="{{ $freq }}" {{ old('interval', $interval->interval) == $freq ? 'selected' : '' }}>{{ $freq }}</option>
                @endforeach
            </select>
        </div>

        {{-- Facilitator --}}
        <div>
            <label for="facilitator" class="block text-sm font-medium text-[#344053] mb-2">Facilitator</label>
            <select name="facilitator" id="facilitator"
                class="w-full px-3.5 py-2.5 bg-white rounded-lg border border-[#cfd4dc] text-[#0f1728] text-base focus:outline-none focus:ring-2 focus:ring-[#6840c6] pr-10 appearance-none">
                @foreach (['Crew', 'Service Provider'] as $fac)
                    <option value="{{ $fac }}" {{ old('facilitator', $interval->facilitator) == $fac ? 'selected' : '' }}>{{ $fac }}</option>
                @endforeach
            </select>
        </div>

        

        {{-- Buttons --}}
        <div class="flex justify-end space-x-4">
            <a href="{{ route('maintenance.intervals.show', [$category, $interval]) }}"
               class="px-4 py-2.5 bg-white text-[#344053] border border-[#cfd4dc] rounded-lg text-sm font-medium hover:bg-[#f9fafb] transition-colors">
                Cancel
            </a>

            <button type="submit"
                class="px-4 py-2.5 bg-[#7e56d8] rounded-lg shadow border border-[#7e56d8] text-white text-sm font-medium hover:bg-[#6840c6] transition-colors">
                <i class="fa-solid fa-check mr-2"></i>
                Update Interval
            </button>
        </div>
    </form>
@endsection
