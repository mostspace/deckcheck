@extends('v2.layouts.app')

@section('title', 'Maintenance Index')

@section('content')

    @php
        $intervalTypes = [
            'Daily',
            'Bi-Weekly',
            'Weekly',
            'Monthly',
            'Quarterly',
            'Bi-Annually',
            'Annual',
            '2-Yearly',
            '3-Yearly',
            '5-Yearly',
            '6-Yearly',
            '10-Yearly',
            '12-Yearly',
        ];
    @endphp

    {{-- Enhanced Maintenance Header --}}
    @include('v2.components.nav.header-routing', [
        'activeTab' => 'index',
        'breadcrumbs' => [
            ['label' => 'Maintenance', 'icon' => asset('assets/media/icons/sidebar-solid-wrench-scredriver.svg')],
            ['label' => 'Index']
        ],
        'actions' => [
            [
                'type' => 'link',
                'label' => 'Create Category',
                'url' => route('maintenance.create'),
                'icon' => 'fas fa-plus',
                'class' => 'bg-primary-500 text-slate-800 hover:bg-primary-600'
            ]
        ]
    ])

    <div class="px-3 sm:px-6 lg:px-8 py-4 sm:py-6">
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-100 border border-red-300 text-red-800 rounded-lg text-sm">
                {{ session('error') }}
            </div>
        @endif

        {{-- Main Content --}}
        <div class="h-full bg-white overflow-y-auto flex flex-col gap-4 sm:gap-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-[#0f1728]">Maintenance Index</h1>
                    <p class="text-[#475466]">Overview of equipment maintenance requirements.</p>
                </div>
            </div>

            <div class="flex flex-col gap-4 sm:gap-6">
                @include('v2.sections.crew.maintenance.index.stat-cards')
                @include('v2.sections.crew.maintenance.index.maintenance-table')
            </div>
        </div>
    </div>

    {{-- Search Filtering --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Search filtering logic
            const searchInput = document.getElementById('category-search');
            const rows = document.querySelectorAll('#category-list tr');

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const query = this.value.toLowerCase();

                    rows.forEach(row => {
                        const category = row.children[0]?.textContent.toLowerCase() || '';
                        const match = category.includes(query);
                        row.style.display = match ? '' : 'none';
                    });
                });
            }
        });
    </script>

    {{-- Column Sort --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const buttons = document.querySelectorAll('.sort-button');
            const tableBody = document.getElementById('category-list');
            let currentSortKey = null;
            let ascending = true;

            buttons.forEach(button => {
                button.addEventListener('click', () => {
                    const sortKey = button.dataset.sortKey;
                    ascending = (currentSortKey === sortKey) ? !ascending : true;
                    currentSortKey = sortKey;

                    const rows = Array.from(tableBody.querySelectorAll('tr'));

                    rows.sort((a, b) => {
                        let aVal = a.dataset[sortKey] || '';
                        let bVal = b.dataset[sortKey] || '';

                        aVal = aVal.toLowerCase();
                        bVal = bVal.toLowerCase();

                        return (aVal > bVal ? 1 : aVal < bVal ? -1 : 0) * (ascending ? 1 : -1);
                    });

                    rows.forEach(row => tableBody.appendChild(row));

                    // Reset all sort icons
                    buttons.forEach(btn => {
                        const icon = btn.querySelector('i');
                        if (icon) {
                            icon.classList.remove('fa-arrow-up-short-wide', 'fa-arrow-down-wide-short');
                            icon.classList.add('fa-sort');
                            icon.classList.remove('text-[#6840c6]');
                            icon.classList.add('text-[#475466]');
                        }
                    });

                    // Update clicked button's icon
                    const icon = button.querySelector('i');
                    if (icon) {
                        icon.classList.remove('fa-sort', 'text-[#475466]');
                        icon.classList.add(
                            ascending ? 'fa-arrow-up-short-wide' : 'fa-arrow-down-wide-short',
                            'text-[#6840c6]'
                        );
                    }
                });
            });
        });
    </script>

@endsection
