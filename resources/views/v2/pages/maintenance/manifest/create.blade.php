@extends('v2.layouts.app')

@section('title', 'Add Equipment')

@section('content')

    @include('v2.components.nav.header-routing', [
        'activeTab' => 'manifest',
        'context' => 'maintenance',
        'breadcrumbs' => [
            ['label' => 'Maintenance', 'icon' => asset('assets/media/icons/sidebar-solid-wrench-scredriver.svg')],
            ['label' => 'Manifest', 'url' => route('maintenance.manifest.index')],
            ['label' => 'Add Equipment', 'active' => true]
        ]
    ])

    <div class="px-3 py-4 sm:px-6 sm:py-6 lg:px-8">
        <div class="mx-auto max-w-2xl">
            <div class="mb-6">
                <h1 class="text-2xl font-semibold text-[#0f1728]">Add Equipment</h1>
                <p class="text-[#475466]">Quickly assign basic information to a new equipment item.</p>
            </div>

            <form action="{{ route('maintenance.manifest.store') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Category --}}
                <div>
                    <label for="category_id" class="mb-2 block text-sm font-medium text-[#344053]">Category</label>
                    <select name="category_id" id="category_id"
                        class="w-full appearance-none rounded-lg border border-[#cfd4dc] bg-white px-3.5 py-2.5 pr-10 text-sm text-[#344053] focus:border-transparent focus:outline-none focus:ring-2 focus:ring-[#6840c6]">
                        <option value="">Select category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Deck --}}
                <div>
                    <label for="deck_id" class="mb-2 block text-sm font-medium text-[#344053]">Deck</label>
                    <select name="deck_id" id="deck_id"
                        class="w-full appearance-none rounded-lg border border-[#cfd4dc] bg-white px-3.5 py-2.5 pr-10 text-sm text-[#344053] focus:border-transparent focus:outline-none focus:ring-2 focus:ring-[#6840c6]">
                        <option value="">Select deck</option>
                        @foreach ($decks as $deck)
                            <option value="{{ $deck->id }}">{{ $deck->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Location (dependent) --}}
                <div>
                    <label for="location_id" class="mb-2 block text-sm font-medium text-[#344053]">Location</label>
                    <select name="location_id" id="location_id"
                        class="w-full appearance-none rounded-lg border border-[#cfd4dc] bg-white px-3.5 py-2.5 pr-10 text-sm text-[#344053] focus:border-transparent focus:outline-none focus:ring-2 focus:ring-[#6840c6]"
                        disabled>
                        <option value="">Select deck first</option>
                    </select>
                </div>

                {{-- Name --}}
                <div>
                    <label for="name" class="mb-2 block text-sm font-medium text-[#344053]">Name</label>
                    <input name="name" id="name"
                        class="w-full appearance-none rounded-lg border border-[#cfd4dc] bg-white px-3.5 py-2.5 pr-10 text-sm text-[#344053] focus:border-transparent focus:outline-none focus:ring-2 focus:ring-[#6840c6]">
                </div>

                {{-- Internal ID --}}
                <div>
                    <label for="internal_id" class="mb-2 block text-sm font-medium text-[#344053]">Custom ID -
                        Optional</label>
                    <input name="internal_id" id="name"
                        class="w-full appearance-none rounded-lg border border-[#cfd4dc] bg-white px-3.5 py-2.5 pr-10 text-sm text-[#344053] focus:border-transparent focus:outline-none focus:ring-2 focus:ring-[#6840c6]">
                </div>

                {{-- Buttons --}}
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('maintenance.manifest.index') }}"
                        class="rounded-lg border border-[#cfd4dc] bg-white px-4 py-2.5 text-sm font-medium text-[#344053] transition-colors hover:bg-[#f9fafb]">
                        Cancel
                    </a>

                    <button type="submit"
                        class="rounded-lg border border-[#7e56d8] bg-[#7e56d8] px-4 py-2.5 text-sm font-medium text-white shadow transition-colors hover:bg-[#6840c6]">
                        <i class="fa-solid fa-plus mr-2"></i>
                        Save Equipment
                    </button>
                </div>
            </form>

            {{-- Dependent Location Loader --}}
            <script>
                document.getElementById('deck_id').addEventListener('change', function() {
                    const deckId = this.value;
                    const locationSelect = document.getElementById('location_id');

                    if (!deckId) {
                        locationSelect.innerHTML = '<option value="">Select deck first</option>';
                        locationSelect.disabled = true;
                        locationSelect.classList.add('bg-gray-100');
                        return;
                    }

                    fetch(`/maintenance/manifest/decks/${deckId}/locations`)
                        .then(response => response.json())
                        .then(locations => {
                            locationSelect.innerHTML = '<option value="">Select location</option>';
                            locations.forEach(loc => {
                                const option = document.createElement('option');
                                option.value = loc.id;
                                option.textContent = loc.name;
                                locationSelect.appendChild(option);
                            });
                            locationSelect.disabled = false;
                            locationSelect.classList.remove('bg-gray-100');
                        });
                });
            </script>
            </form>
        </div>
    </div>
@endsection
