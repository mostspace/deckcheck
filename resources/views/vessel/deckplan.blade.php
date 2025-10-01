@extends('layouts.app')

@section('title', 'Deck Plan')

@section('content')

    {{-- Header --}}
    <div class="mb-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-[#0f1728]">Deck Plan</h1>
                <p class="text-[#475466]">Manage vessel decks and equipment locations for inspection tracking.</p>
            </div>
        </div>
    </div>
    <div class="mb-6 flex items-center gap-4">
        <button onclick="window.location='{{ route('vessel.decks.create') }}'"
            class="rounded-lg border border-[#7e56d8] bg-[#7e56d8] px-4 py-2.5 text-sm font-medium text-white shadow-[0px_1px_2px_0px_rgba(16,24,40,0.05)] transition-colors hover:bg-[#6840c6]">
            <i class="fa-solid fa-plus mr-2"></i>
            Add Deck
        </button>
    </div>

    {{-- Deck Expandable Tables --}}
    <div class="space-y-6">

        @forelse($decks as $deck)
            @php
                $deckId = 'deck-' . $deck->id;
            @endphp

            {{-- Deck Header --}}
            <div class="rounded-lg border border-[#e4e7ec] bg-white shadow-[0px_1px_2px_0px_rgba(16,24,40,0.05)]">
                <div class="border-b border-[#e4e7ec]">
                    <div class="flex cursor-pointer items-center justify-between px-6 py-4 hover:bg-[#f8f9fb]"
                        onclick="toggleDeck('{{ $deckId }}')">
                        <div class="flex items-center gap-4">
                            <i id="{{ $deckId }}-arrow"
                                class="fa-solid fa-chevron-down text-[#667084] transition-transform duration-300"></i>
                            <div>
                                <h3 class="text-lg font-semibold text-[#000000]">{{ $deck->name }}</h3>
                                <p class="text-sm text-[#475466]">
                                    {{ $deck->locations->count() > 0
                                        ? $deck->locations->count() . ' ' . Str::plural('Location', $deck->locations->count())
                                        : 'No Locations' }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button onclick="window.location='{{ route('vessel.decks.show', $deck) }}'"
                                class="rounded-lg p-2 text-[#667084] hover:bg-[#f8f9fb] hover:text-[#344053]">
                                <i class="fa-solid fa-edit"></i>
                            </button>

                            {{-- Delete Deck --}}
                            <form action="{{ route('vessel.decks.destroy', $deck) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this deck? All associated locations will also be deleted');">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="rounded-lg p-2 text-[#667084] hover:bg-[#fef3f2] hover:text-[#f04438]">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Deck Table --}}
                <div id="{{ $deckId }}-content" class="">
                    <table class="w-full table-fixed">

                        {{-- Table Header --}}
                        <thead class="border-b border-[#e4e7ec] bg-[#f8f9fb]">
                            <tr>
                                <th class="px-6 py-3 text-left">
                                    <span class="text-xs font-medium uppercase tracking-wider text-[#7e56d8]">Location
                                        Name</span>
                                </th>
                                <th class="px-6 py-3 text-left">
                                    <span
                                        class="text-xs font-medium uppercase tracking-wider text-[#7e56d8]">Description</span>
                                </th>
                                <th class="px-6 py-3 text-left">
                                    <span
                                        class="text-xs font-medium uppercase tracking-wider text-[#7e56d8]">Equipment</span>
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-[#e4e7ec]" id="locations-list">

                            {{-- Locations Loop --}}
                            @if ($deck->locations->isNotEmpty())
                                @foreach ($deck->locations as $loc)
                                    <tr class="group hover:bg-[#f8f9fb]">
                                        <td class="px-6 py-4 font-medium text-[#0f1728]">{{ $loc->name }}</td>
                                        <td class="px-6 py-4 text-sm text-[#475466]">{{ $loc->description }}</td>
                                        <td class="px-6 py-4 text-[#475466]">*In Dev*</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-[#667084]">No Locations
                                        Defined on this Deck.</td>
                                </tr>
                            @endif

                        </tbody>
                    </table>
                </div>
            </div>

        @empty

            <p class="text-gray-500">No decks have been defined for this vessel yet.</p>

        @endforelse

    </div>

    <script>
        function toggleDeck(deckId) {
            const content = document.getElementById(`${deckId}-content`);
            const arrow = document.getElementById(`${deckId}-arrow`);

            content.classList.toggle('hidden');
            arrow.classList.toggle('rotate-180');
        }
    </script>

@endsection
