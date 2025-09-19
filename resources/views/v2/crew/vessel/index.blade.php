@extends('v2.layouts.app')

@section('title', 'Vessel Management')

@section('content')

    @include('v2.components.navigation.header', [
        'tabs' => [
            ['id' => 'information', 'label' => 'Information', 'active' => true],
            ['id' => 'crew', 'label' => 'Crew', 'active' => false],
            ['id' => 'deck_plan', 'label' => 'Deck Plan', 'active' => false]
        ],
        'pageName' => 'Vessel',
        'pageIcon' => asset('assets/media/icons/sidebar-solid-boat.svg'),
        'activeTab' => 'Information'
    ])

    <div class="px-3 sm:px-6 lg:px-8 py-4 sm:py-6">

        {{-- Information Tab Panel --}}
        <div id="panel-information" class="tab-panel" role="tabpanel" aria-labelledby="tab-information">
            <div class="mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-semibold text-[#0f1728]">Vessel Information</h1>
                        <p class="text-[#475466]">Manage vessel details and contact information</p>
                    </div>
                </div>
            </div>
    
    
            <div class="relative mb-8">
                <div class="h-48 rounded-xl overflow-hidden bg-gradient-to-r from-[#6840c6] to-[#7e56d8] relative">
                    <img class="w-full h-full object-cover" src="https://storage.googleapis.com/uxpilot-auth.appspot.com/09fb5435f6-45bae26d661aa26fc2fe.png" alt="luxury yacht sailing on blue ocean waters">
                    <div class="absolute inset-0 bg-black bg-opacity-30"></div>
                    
                    <!-- Edit Icon -->
                    <button class="absolute top-4 right-4 w-10 h-10 bg-white bg-opacity-90 hover:bg-opacity-100 rounded-lg flex items-center justify-center text-[#344053] hover:text-[#6840c6] transition-all">
                        <i class="fa-solid fa-pen text-sm"></i>
                    </button>
                    
                    <!-- Vessel Name Overlay -->
                    <div class="absolute bottom-6 left-6">
                        <div class="flex items-center gap-3">
                            <h1 class="text-4xl font-bold text-white">{{ $vessel->type }} {{ $vessel->name }}</h1>
                            <img src="https://flagcdn.com/w20/us.png" alt="Registry Flag" class="w-8 h-5">
                        </div>
                    </div>
                </div>
            </div>        
    
            <!-- Vessel Information Display -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-6">
                    <div></div>
                    <button class="text-sm text-[#6840c6] hover:text-[#7e56d8] font-medium flex items-center gap-2">
                        <i class="fa-solid fa-flag text-xs"></i>
                        Report Inaccurate Information
                    </button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-[#667084] mb-1">Make</label>
                        <p class="text-[#0f1728] font-medium">{{ $vessel->vessel_make }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-[#667084] mb-1">Year</label>
                        <p class="text-[#0f1728] font-medium">{{ $vessel->build_year }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-[#667084] mb-1">LOA</label>
                        <p class="text-[#0f1728] font-medium">{{ $vessel->vessel_loa }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-[#667084] mb-1">LWL</label>
                        <p class="text-[#0f1728] font-medium">{{ $vessel->vessel_lwl }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-[#667084] mb-1">Beam</label>
                        <p class="text-[#0f1728] font-medium">{{ $vessel->vessel_beam }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-[#667084] mb-1">Gross Tonnage</label>
                        <p class="text-[#0f1728] font-medium">{{ $vessel->vessel_gt }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-[#667084] mb-1">Official Number</label>
                        <p class="text-[#0f1728] font-medium">{{ $vessel->official_number }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-[#667084] mb-1">IMO Number</label>
                        <p class="text-[#0f1728] font-medium">{{ $vessel->imo_number }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-[#667084] mb-1">MMSI Number</label>
                        <p class="text-[#0f1728] font-medium">{{ $vessel->mmsi_number }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-[#667084] mb-1">Call Sign</label>
                        <p class="text-[#0f1728] font-medium">{{ $vessel->callsign }}</p>
                    </div>
                </div>
            </div>              
    
            <!-- Vessel Home Port Section -->
            <div class="bg-white rounded-lg shadow-[0px_1px_2px_0px_rgba(16,24,40,0.05)] border border-[#e4e7ec] p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-[#0f1728]">Vessel Home Port</h2>
                    <button class="text-sm text-[#6840c6] hover:text-[#7e56d8] font-medium">Edit</button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-[#344053] mb-2">Port</label>
                        <input type="text" value="{{ $vessel->registry_port }}" class="w-full px-3 py-2 border border-[#cfd4dc] rounded-lg text-[#0f1728] bg-[#f8f9fb]" readonly="">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#344053] mb-2">Country</label>
                        <input type="text" value="{{ $vessel->flag }}" class="w-full px-3 py-2 border border-[#cfd4dc] rounded-lg text-[#0f1728] bg-[#f8f9fb]" readonly="">
                    </div>
                </div>
            </div>
    
            <!-- Vessel Contact Section -->
            <div class="bg-white rounded-lg shadow-[0px_1px_2px_0px_rgba(16,24,40,0.05)] border border-[#e4e7ec] p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-[#0f1728]">Vessel Contact</h2>
                    <button class="text-sm text-[#6840c6] hover:text-[#7e56d8] font-medium">Edit</button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-[#344053] mb-2">Satellite Phone</label>
                        <input type="text" value="{{ $vessel->vessel_phone }}" class="w-full px-3 py-2 border border-[#cfd4dc] rounded-lg text-[#0f1728] bg-[#f8f9fb]" readonly="">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#344053] mb-2">Email</label>
                        <input type="email" value="{{ $vessel->vessel_email }}" class="w-full px-3 py-2 border border-[#cfd4dc] rounded-lg text-[#0f1728] bg-[#f8f9fb]" readonly="">
                    </div>
                </div>
            </div>
    
            <!-- Designated Person Ashore Section -->
            <div class="bg-white rounded-lg shadow-[0px_1px_2px_0px_rgba(16,24,40,0.05)] border border-[#e4e7ec] p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-[#0f1728]">Designated Person Ashore</h2>
                    <button class="text-sm text-[#6840c6] hover:text-[#7e56d8] font-medium">Edit</button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-[#344053] mb-2">Name</label>
                        <input type="text" value="{{ $vessel->dpa_name }}" class="w-full px-3 py-2 border border-[#cfd4dc] rounded-lg text-[#0f1728] bg-[#f8f9fb]" readonly="">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#344053] mb-2">Phone</label>
                        <input type="text" value="{{ $vessel->dpa_phone }}" class="w-full px-3 py-2 border border-[#cfd4dc] rounded-lg text-[#0f1728] bg-[#f8f9fb]" readonly="">
                    </div>
                </div>
            </div>
        </div>

        {{-- Crew Tab Panel --}}
        <div id="panel-crew" class="tab-panel hidden" role="tabpanel" aria-labelledby="tab-crew">
            @include('v2.crew.vessel.crew.index')
        </div>

        {{-- Deck Plan Tab Panel --}}
        <div id="panel-deck_plan" class="tab-panel hidden" role="tabpanel" aria-labelledby="tab-deck_plan">
            @include('v2.crew.vessel.deck-plan.index')
        </div>

    </div>
@endsection