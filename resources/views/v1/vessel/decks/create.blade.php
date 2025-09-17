@extends('layouts.form-page')

@php
    $title = 'New Deck';
    $subtitle = 'Add a new deck to your vessel.';
@endphp

@section('form')
<form action="{{ route('vessel.decks.store') }}" method="POST" class="space-y-6">
    @csrf
    <div>
        <label for="name" class="block text-sm font-medium text-[#344053] mb-2">Deck Name</label>
        <input name="name" id="name" value="{{ old('name') }}" type="text"
               class="w-full px-3.5 py-2.5 bg-white rounded-lg border border-[#cfd4dc] text-[#0f1728] text-base focus:border-[#6840c6] focus:outline-none">
    </div>
    <div class="flex justify-end space-x-4">
        <a href="{{ route('vessel.deckplan') }}" class="px-4 py-2.5 bg-white text-[#344053] border border-[#cfd4dc] rounded-lg text-sm font-medium hover:bg-[#f9fafb]">
            Cancel
        </a>
        <button type="submit" class="px-4 py-2.5 bg-[#7e56d8] text-white rounded-lg text-sm font-medium hover:bg-[#6840c6]">
            Save Deck
        </button>
    </div>
</form>
@endsection