@extends('layouts.form-page')

@php
    $title = 'New Location';
    $subtitle = "Define new location on {$deck->name}.";
@endphp

@section('form')
    <form action="{{ route('decks.locations.store', $deck->id) }}" method="POST" class="space-y-6">
        @csrf

        {{-- Location Name --}}
        <div>
            <label for="name" class="mb-2 block text-sm font-medium text-[#344053]">Location Name</label>
            <input name="name" id="name" value="{{ old('name') }}" type="text"
                class="w-full rounded-lg border border-[#cfd4dc] bg-white px-3.5 py-2.5 text-base text-[#0f1728] placeholder-[#667084] focus:border-[#6840c6] focus:outline-none"
                placeholder="Enter location name">
        </div>

        {{-- Description --}}
        <div>
            <label for="description" class="mb-2 block text-sm font-medium text-[#344053]">Location Description</label>
            <textarea name="description" id="description" rows="4"
                class="w-full resize-none rounded-lg border border-[#cfd4dc] bg-white px-3.5 py-2.5 text-base text-[#0f1728] placeholder-[#667084] focus:border-[#6840c6] focus:outline-none"
                placeholder="Enter description">{{ old('description') }}</textarea>
        </div>

        {{-- Buttons --}}
        <div class="flex justify-end space-x-4">
            <a href="{{ route('vessel.decks.show', $deck->id) }}"
                class="rounded-lg border border-[#cfd4dc] bg-white px-4 py-2.5 text-sm font-medium text-[#344053] transition-colors hover:bg-[#f9fafb]">
                Cancel
            </a>

            <button type="submit"
                class="rounded-lg border border-[#7e56d8] bg-[#7e56d8] px-4 py-2.5 text-sm font-medium text-white shadow transition-colors hover:bg-[#6840c6]">
                <i class="fa-solid fa-plus mr-2"></i>
                Save Location
            </button>
        </div>
    </form>
@endsection
