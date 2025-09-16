@extends('layouts.app')

@section('title', 'Deck Overview | '.$deck->name)

@section('content')

{{--Header--}}            
    <div class="mb-6">
         
        {{--Breadcrumb--}}
        <div class="flex items-center gap-2 text-sm text-[#475466] mb-3">
            <a href="{{ route('vessel.deckplan') }}" class="cursor-pointer hover:text-[#6840c6]">Deck Plan</a>
                <i class="fa-solid fa-chevron-right text-xs"></i>
                <span class="text-[#0f1728] font-medium">{{ $deck->name }}</span>
        </div>

        {{--System Messages--}}
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif
        
        <h1 class="text-2xl font-semibold text-[#0f1728]">{{ $deck->name }} Overview</h1>
        <p class="text-[#475466]">Manage {{ $deck->name }} equipment locations and inspection points.</p>

    </div>

{{--Page Controls--}}
    <div class="mb-6 flex items-center justify-between gap-4">
        <div class="flex items-center gap-4">
                        
            {{--Search--}}
            <div class="w-80">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-search text-[#667084]"></i>
                    </div>
                    <input type="text" id="location-search" placeholder="Search locations..." class="w-full pl-10 pr-4 py-2.5 bg-white border border-[#cfd4dc] rounded-lg text-sm placeholder-[#667084] focus:ring-2 focus:ring-[#6840c6] focus:border-[#6840c6] outline-none">
                </div>
            </div>
            
            {{--Create Button--}}
            <button onclick="window.location='{{ route('vessel.decks.locations.create', $deck->id) }}'" id="add-location-btn" class="px-4 py-2.5 bg-[#7e56d8] rounded-lg shadow-[0px_1px_2px_0px_rgba(16,24,40,0.05)] border border-[#7e56d8] text-white text-sm font-medium hover:bg-[#6840c6] transition-colors">
                <i class="fa-solid fa-plus mr-2"></i>
                Add Location
            </button> 
        
        </div>              
    </div>

{{--Locations Table--}}
    <div class="bg-white rounded-lg border border-[#e4e7ec] shadow-[0px_1px_2px_0px_rgba(16,24,40,0.05)]" id="locations-table">
        <div class="overflow-x-auto">
            <table class="w-full table-fixed">

            {{--Table Header--}}
            <thead class="bg-[#f8f9fb] border-b border-[#e4e7ec]">
                <tr>
                    <th class="px-6 py-3 text-left">
                        <span class="text-xs font-medium text-[#7e56d8] uppercase tracking-wider">Display Order</span>
                    </th>
                    <th class="px-6 py-3 text-left">
                        <span class="text-xs font-medium text-[#7e56d8] uppercase tracking-wider">Location Name</span>
                    </th>
                    <th class="px-6 py-3 text-left">
                        <span class="text-xs font-medium text-[#7e56d8] uppercase tracking-wider">Description</span>
                    </th>
                    <th class="px-6 py-3 text-left">
                        <span class="text-xs font-medium text-[#7e56d8] uppercase tracking-wider">Equipment</span>
                    </th>
                    <th class="px-6 py-3 text-left">
                        <span class="text-xs font-medium text-[#7e56d8] uppercase tracking-wider">Actions</span>
                    </th>
                </tr>
            </thead>

            {{--Locations Loop--}}
                <tbody class="divide-y divide-[#e4e7ec]" id="locations-list">
                    
                    @if($deck->locations->isNotEmpty())
                        @foreach($deck->locations as $loc)
                            
                            <tr data-id="{{ $loc->id }}" class="hover:bg-[#f8f9fb] group">
                                <td class="px-6 py-4 ">
                                    <i class="fa-solid fa-grip-vertical text-[#667084] cursor-move"></i>
                                </td>
                                <td class="px-6 py-4 text-[#0f1728] font-medium">{{ $loc->name }}</td>
                                <td class="px-6 py-4 text-[#475466] text-sm">{{ $loc->description }}</td>
                                <td class="px-6 py-4 text-[#475466]">*In Dev*</td>
                                <td class="px-6 py-4 flex gap-2">
                                    
                                    {{--Edit--}}
                                    <a href="{{ route('locations.edit', $loc) }}"
                                        class="p-2 text-[#667084] hover:text-[#344053] hover:bg-[#f8f9fb] rounded-lg">
                                            <i class="fa-solid fa-edit"></i>
                                    </a>
                                    
                                    {{--Delete--}}
                                    <form action="{{ route('locations.destroy', $loc) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this location?');">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                            class="p-2 text-[#667084] hover:text-[#f04438] hover:bg-[#fef3f2] rounded-lg">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>

                                </td>
                            </tr>

                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-[#667084]">No Locations Defined on this Deck.</td>
                        </tr>
                    @endif
                
                </tbody>
            </table>
        </div>
    </div>


{{--Sortable--}}
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            new Sortable(document.querySelector('#locations-list'), {
            animation: 150,
            onEnd: function (evt) {
                // grab the new order of IDs
                const order = Array.from(
                document.querySelectorAll('#locations-list tr')
                ).map((tr, idx) => ({
                id: tr.dataset.id,
                display_order: idx + 1
                }));

                // send it to your backend
                fetch(@json(route('locations.reorder', $deck->id)), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': @json(csrf_token())
                },
                body: JSON.stringify({order})
                })
                .then(res => {
                if (!res.ok) throw new Error('Reorder failed');
                })
                .catch(console.error);
            }
            });
        });
    </script>


{{--Search Filtering--}}
    <script>
    document.addEventListener('DOMContentLoaded', function () {

        // Sortable logic (already in your file)...
        new Sortable(document.querySelector('#locations-list'), { /* your existing config */ });

        // Search filtering logic
        const searchInput = document.getElementById('location-search');
        const rows = document.querySelectorAll('#locations-list tr');

        searchInput.addEventListener('input', function () {
            const query = this.value.toLowerCase();

            rows.forEach(row => {
                const name = row.children[1]?.textContent.toLowerCase() || '';
                const description = row.children[2]?.textContent.toLowerCase() || '';

                const match = name.includes(query) || description.includes(query);
                row.style.display = match ? '' : 'none';
            });
        });
    });
</script>

  
@endsection