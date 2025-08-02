@extends('layouts.admin')

@section('title', $vessel->name)

@section('content')

    <div id="vessel-detail-content" class="p-6">
        <div id="vessel-hero" class="relative bg-[#243b53] rounded-lg border border-[#334e68] overflow-hidden mb-6">
            <div class="relative h-64">
                <img class="w-full h-full object-cover" src="https://storage.googleapis.com/uxpilot-auth.appspot.com/f9731b3dca-1d79086e877da256afd7.png" alt="{{ $vessel->name }}">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                <div class="absolute bottom-6 left-6 right-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <span class="text-4xl mr-4">{{ $vessel->flag_emoji }}</span>
                            <div>
                                <h1 class="text-3xl font-bold text-white mb-1">{{ $vessel->name }}</h1>
                                <p class="text-gray-400 text-sm">
                                    {{ $vessel->type }} • IMO: {{ $vessel->imo_number }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <span class="px-3 py-1 backdrop-blur-sm text-sm rounded-full {{ $vessel->is_active ? 'bg-green-600/90' : 'bg-red-600/90' }}">
                                {{ $vessel->is_active ? 'Active' : 'Inactive' }}
                            </span>
                            @if ($vessel->subscription)
                                <span class="px-3 py-1 bg-blue-600/90 backdrop-blur-sm text-sm rounded-full">
                                    {{ $vessel->subscription->plan_name }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <div id="vessel-stats" class="lg:col-span-3 grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-[#243b53] rounded-lg border border-[#334e68] p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm">Total Users</p>
                            <p class="text-white text-2xl font-semibold">
                                {{ $vessel->boardings->count() }}
                            </p>
                        </div>
                        <i class="fa-solid fa-users text-blue-400 text-xl"></i>
                    </div>
                </div>
                <div class="bg-[#243b53] rounded-lg border border-[#334e68] p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm">Active Today</p>
                            <p class="text-white text-2xl font-semibold">
                                {{ $vessel->boardings->where('last_active_at', '>=', now()->startOfDay())->count() }}
                            </p>
                        </div>
                        <i class="fa-solid fa-user-check text-green-400 text-xl"></i>
                    </div>
                </div>
                <div class="bg-[#243b53] rounded-lg border border-[#334e68] p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm">S3 Usage</p>
                            <p class="text-white text-2xl font-semibold">
                                {{ number_format($vessel->data_usage_gb, 1) }}GB
                            </p>
                        </div>
                        <i class="fa-solid fa-database text-[#05A0D1] text-xl"></i>
                    </div>
                </div>
                <div class="bg-[#243b53] rounded-lg border border-[#334e68] p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm">DB Usage</p>
                            <p class="text-white text-2xl font-semibold">
                                {{ number_format($vessel->data_usage_gb, 1) }}GB
                            </p>
                        </div>
                        <i class="fa-solid fa-database text-[#E39211] text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                {{-- Vessel Information --}}
                <div id="vessel-info-section" class="bg-[#243b53] rounded-lg border border-[#334e68] p-6">
                    <h2 class="text-lg font-semibold mb-4 flex items-center">
                        <i class="fa-solid fa-ship mr-2 text-blue-400"></i>
                        Vessel Information
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-400 text-sm font-medium mb-1">Port of Registry</label>
                            <p class="text-white">{{ $vessel->registry_port }}</p>
                        </div>
                        <div>
                            <label class="block text-gray-400 text-sm font-medium mb-1">Build Year</label>
                            <p class="text-white">{{ $vessel->build_year }}</p>
                        </div>
                        <div>
                            <label class="block text-gray-400 text-sm font-medium mb-1">Vessel Make</label>
                            <p class="text-white">{{ $vessel->vessel_make }}</p>
                        </div>
                        <div>
                            <label class="block text-gray-400 text-sm font-medium mb-1">Official Number</label>
                            <p class="text-white">{{ $vessel->official_number }}</p>
                        </div>
                        <div>
                            <label class="block text-gray-400 text-sm font-medium mb-1">IMO Number</label>
                            <p class="text-white">{{ $vessel->imo_number }}</p>
                        </div>
                        <div>
                            <label class="block text-gray-400 text-sm font-medium mb-1">MMSI Number</label>
                            <p class="text-white">{{ $vessel->mmsi_number }}</p>
                        </div>
                        <div>
                            <label class="block text-gray-400 text-sm font-medium mb-1">Callsign</label>
                            <p class="text-white">{{ $vessel->callsign }}</p>
                        </div>
                    </div>
                </div>

                {{-- Technical Specs --}}
                <div id="technical-specs-display" class="bg-[#243b53] rounded-lg border border-[#334e68] p-6">
                    <h2 class="text-lg font-semibold mb-4 flex items-center">
                        <i class="fa-solid fa-cog mr-2 text-yellow-400"></i>
                        Technical Specifications
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-gray-400 text-sm font-medium mb-1">Length</label>
                            <p class="text-white">{{ $vessel->vessel_size }}m</p>
                        </div>
                        <div>
                            <label class="block text-gray-400 text-sm font-medium mb-1">LWL</label>
                            <p class="text-white">{{ number_format($vessel->vessel_lwl) }}</p>
                        </div>
                        <div>
                            <label class="block text-gray-400 text-sm font-medium mb-1">Width</label>
                            <p class="text-white">{{ $vessel->vessel_beam }}m</p>
                        </div>
                        <div>
                            <label class="block text-gray-400 text-sm font-medium mb-1">Draft</label>
                            <p class="text-white">{{ $vessel->vessel_draft }}m</p>
                        </div>
                        <div>
                            <label class="block text-gray-400 text-sm font-medium mb-1">Gross Tonnage</label>
                            <p class="text-white">{{ number_format($vessel->vessel_gt) }}</p>
                        </div>
                    </div>
                </div>

                {{-- Active Users Table --}}
                <div id="users-table-section" class="bg-[#243b53] rounded-lg border border-[#334e68] p-6">
                    <h2 class="text-lg font-semibold mb-4 flex items-center">
                        <i class="fa-solid fa-users mr-2 text-green-400"></i>
                        Active Users
                    </h2>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-[#334e68]">
                                    <th class="text-left py-2 text-gray-400">Name</th>
                                    <th class="text-left py-2 text-gray-400">Department</th>
                                    <th class="text-left py-2 text-gray-400">Role</th>
                                    <th class="text-left py-2 text-gray-400">Permissions</th>
                                    <th class="text-left py-2 text-gray-400">Last Active</th>
                                    <th class="text-left py-2 text-gray-400">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#334e68]">
                                @foreach ($vessel->boardings as $boarding)
                                    <tr>
                                        <td class="py-3 flex items-center">
                                            <img src="{{ Storage::url($boarding->user->profile_pic) }}" class="w-8 h-8 rounded-full mr-3"
                                                alt="{{ $boarding->user->full_name }}">
                                            {{ $boarding->user->full_name }}
                                        </td>
                                        <td class="py-3">{{ $boarding->department ?? '—' }}</td>
                                        <td class="py-3">{{ $boarding->role ?? '—' }}</td>
                                        <td class="py-3">{{ $boarding->access_level ?? '—' }}</td>
                                        <td class="py-3">Placeholder</td>
                                        <td class="py-3">
                                            <span
                                                class="px-2 py-1 text-xs rounded-full capitalize {{ $boarding->status === 'active' ? 'bg-green-600' : 'bg-gray-600' }}">
                                                {{ $boarding->status ?? '—' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Owner Information --}}
            <div class="space-y-6">
                <div id="owner-info-display" class="bg-[#243b53] rounded-lg border border-[#334e68] p-6">
                    <h2 class="text-lg font-semibold mb-4 flex items-center">
                        <i class="fa-solid fa-user mr-2 text-green-400"></i>
                        Owner Information
                    </h2>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <img src="{{ Storage::url($vessel->owner->profile_pic) }}}" class="w-12 h-12 rounded-full mr-3" alt="{{ $vessel->owner->full_name }}">
                            <div>
                                <p class="font-medium text-white">{{ $vessel->owner->full_name }}</p>
                                <p class="text-sm text-gray-400">**Role**</p>
                            </div>
                        </div>
                        <div>
                            <label class="block text-gray-400 text-sm font-medium mb-1">Email</label>
                            <p class="text-white text-sm">{{ $vessel->owner->email }}</p>
                        </div>
                        <div>
                            <label class="block text-gray-400 text-sm font-medium mb-1">Phone</label>
                            <p class="text-white text-sm">{{ $vessel->owner->phone }}</p>
                        </div>
                        <button class="w-full bg-blue-600 hover:bg-blue-500 px-4 py-2 rounded text-sm text-white transition-colors mt-4">
                            <i class="fa-solid fa-envelope mr-2"></i>
                            Contact Owner
                        </button>
                    </div>
                </div>


                {{-- Subscription Details --}}
                <div id="subscription-display" class="bg-[#243b53] rounded-lg border border-[#334e68] p-6">
                    <h2 class="text-lg font-semibold mb-4 flex items-center">
                        <i class="fa-solid fa-credit-card mr-2 text-purple-400"></i>
                        Subscription Details
                    </h2>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-gray-400 text-sm font-medium mb-1">Plan</label>
                            <p class="text-white">BETA</p>
                        </div>
                        <div>
                            <label class="block text-gray-400 text-sm font-medium mb-1">Status</label>
                            <span class="px-2 py-1 bg-green-600 text-xs rounded-full">Active</span>
                        </div>
                        <div>
                            <label class="block text-gray-400 text-sm font-medium mb-1">Next Billing</label>
                            <p class="text-white">N/A</p>
                        </div>
                        <div>
                            <label class="block text-gray-400 text-sm font-medium mb-1">Max Users</label>
                            <p class="text-white">N/A</p>
                        </div>
                        <div>
                            <label class="block text-gray-400 text-sm font-medium mb-1">Monthly Cost</label>
                            <p class="text-white font-semibold">N/A</p>
                        </div>
                    </div>
                </div>

                {{-- System Health --}}
                <div id="system-health" class="bg-[#243b53] rounded-lg border border-[#334e68] p-6">
                    <h2 class="text-lg font-semibold mb-4 flex items-center">
                        <i class="fa-solid fa-heart-pulse mr-2 text-red-400"></i>
                        System Health
                    </h2>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-400">Connection Status</span>
                            <span class="px-2 py-1 bg-green-600 text-xs rounded-full">Connected</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-400">Data Sync</span>
                            <span class="px-2 py-1 bg-green-600 text-xs rounded-full">Synced</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-400">Last Backup</span>
                            <span class="text-sm text-gray-400">
                                ##
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-400">Storage Used</span>
                            <span class="text-sm text-gray-400">
                                ##GB
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Quick Actions --}}
                <div id="quick-actions" class="bg-[#243b53] rounded-lg border border-[#334e68] p-6">
                    <h2 class="text-lg font-semibold mb-4 flex items-center">
                        <i class="fa-solid fa-bolt mr-2 text-orange-400"></i>
                        Quick Actions
                    </h2>
                    <div class="space-y-2">
                        <button class="w-full bg-[#334e68] hover:bg-[#3f5b7b] px-4 py-2 rounded text-sm text-white transition-colors text-left">
                            <i class="fa-solid fa-download mr-2"></i> Export Data
                        </button>
                        <button class="w-full bg-[#334e68] hover:bg-[#3f5b7b] px-4 py-2 rounded text-sm text-white transition-colors text-left">
                            <i class="fa-solid fa-sync mr-2"></i> Force Sync
                        </button>
                        <button class="w-full bg-[#334e68] hover:bg-[#3f5b7b] px-4 py-2 rounded text-sm text-white transition-colors text-left">
                            <i class="fa-solid fa-bell mr-2"></i> Send Alert
                        </button>
                        <button class="w-full bg-[#334e68] hover:bg-[#3f5b7b] px-4 py-2 rounded text-sm text-white transition-colors text-left">
                            <i class="fa-solid fa-ban mr-2"></i> Suspend Access
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
