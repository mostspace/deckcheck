@extends('layouts.form-page')

@php
    $title = 'New Deck';
    $subtitle = 'Add a new deck to your vessel.';
@endphp

@section('form')
    <form action="{{ route('vessel.decks.store') }}" method="POST" class="space-y-6">
        @csrf
        <div>
            <label for="name" class="mb-2 block text-sm font-medium text-[#344053]">Deck Name</label>
            <input name="name" id="name" value="{{ old('name') }}" type="text"
                class="w-full rounded-lg border border-[#cfd4dc] bg-white px-3.5 py-2.5 text-base text-[#0f1728] focus:border-[#6840c6] focus:outline-none">
        </div>
        <div class="flex justify-end space-x-4">
            <a href="{{ route('vessel.deckplan') }}"
                class="rounded-lg border border-[#cfd4dc] bg-white px-4 py-2.5 text-sm font-medium text-[#344053] hover:bg-[#f9fafb]">
                Cancel
            </a>
            <button type="submit"
                class="rounded-lg bg-[#7e56d8] px-4 py-2.5 text-sm font-medium text-white hover:bg-[#6840c6]">
                Save Deck
            </button>
        </div>
    </form>
@endsection
