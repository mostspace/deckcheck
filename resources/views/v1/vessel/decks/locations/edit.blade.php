@extends('layouts.form-page')

@php
    $title = 'Edit Location';
    $subtitle = "Update details for {$location->name}.";
@endphp

@section('form')
    <form action="{{ route('locations.update', $location) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Name --}}
        <div>
            <label for="name" class="block text-sm font-medium text-[#344053] mb-2">Location Name</label>
            <input name="name" id="name" value="{{ old('name', $location->name) }}" type="text"
                class="w-full px-3.5 py-2.5 bg-white rounded-lg border border-[#cfd4dc] text-[#0f1728] text-base focus:border-[#6840c6] focus:outline-none placeholder-[#667084]">
        </div>

        {{-- Description --}}
        <div>
            <label for="description" class="block text-sm font-medium text-[#344053] mb-2">Location Description</label>
            <textarea name="description" id="description" rows="4"
                class="w-full px-3.5 py-2.5 bg-white rounded-lg border border-[#cfd4dc] text-[#0f1728] text-base focus:border-[#6840c6] focus:outline-none placeholder-[#667084] resize-none">{{ old('description', $location->description) }}</textarea>
        </div>

        {{-- Buttons --}}
        <div class="flex justify-end space-x-4">
            <a href="{{ route('vessel.decks.show', $location->deck_id) }}"
               class="px-4 py-2.5 bg-white text-[#344053] border border-[#cfd4dc] rounded-lg text-sm font-medium hover:bg-[#f9fafb] transition-colors">
                Cancel
            </a>

            <button type="submit"
                class="px-4 py-2.5 bg-[#7e56d8] rounded-lg shadow border border-[#7e56d8] text-white text-sm font-medium hover:bg-[#6840c6] transition-colors">
                <i class="fa-solid fa-check mr-2"></i>
                Save Changes
            </button>
        </div>
    </form>
@endsection
