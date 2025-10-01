@extends('layouts.admin')

@section('title', $vessel->name)

@section('content')
    @if (session('success'))
        <div class="relative mb-6 rounded border border-green-500 bg-green-600 px-4 py-3 text-white" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
            <button type="button" class="absolute bottom-0 right-0 top-0 px-4 py-3" onclick="this.parentElement.remove()">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
    @endif

    @if (session('info'))
        <div class="relative mb-6 rounded border border-blue-500 bg-blue-600 px-4 py-3 text-white" role="alert">
            <span class="block sm:inline">{{ session('info') }}</span>
            <button type="button" class="absolute bottom-0 right-0 top-0 px-4 py-3" onclick="this.parentElement.remove()">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="relative mb-6 rounded border border-red-500 bg-red-600 px-4 py-3 text-white" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
            <button type="button" class="absolute bottom-0 right-0 top-0 px-4 py-3" onclick="this.parentElement.remove()">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
    @endif

    <div id="vessel-detail-content" class="p-6">
        <div id="vessel-hero" class="relative mb-6 overflow-hidden rounded-lg border border-[#334e68] bg-[#243b53]">
            <div class="relative h-64">
                <img class="h-full w-full object-cover"
                    src="{{ $vessel->hero_photo ? Storage::url($vessel->hero_photo) : asset('images/placeholders/placeholder.png') }}"
                    alt="{{ $vessel->name }}">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                <div class="absolute bottom-6 left-6 right-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <span class="mr-4 text-4xl">{{ $vessel->flag_emoji }}</span>
                            <div>
                                <div class="flex items-center text-xl">
                                    @php
                                        $countryCode = strtolower($vessel->flag);
                                    @endphp

                                    <img src="https://flagcdn.com/h20/{{ $countryCode }}.png" alt="{{ $vessel->flag }}"
                                        class="mr-2 h-4 w-5 rounded-sm object-cover" onerror="this.style.display='none'">

                                    <span class="font-medium text-white">{{ $vessel->type }} {{ $vessel->name }}</span>
                                </div>
                                <p class="text-sm text-gray-400">
                                    Created: {{ $vessel->created_at ? $vessel->created_at->format('F j, Y') : 'Unknown' }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <span class="rounded-full bg-green-600/90 px-3 py-1 text-sm backdrop-blur-sm">
                                Active
                            </span>
                            @if ($vessel->subscription)
                                <span class="rounded-full bg-blue-600/90 px-3 py-1 text-sm backdrop-blur-sm">
                                    {{ $vessel->subscription->plan_name }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="mb-6 flex justify-end space-x-3">
            @if (in_array(auth()->user()->system_role, ['superadmin', 'staff', 'dev']))
                <form action="{{ route('vessel.switch') }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="vessel_id" value="{{ $vessel->id }}">
                    <button type="submit"
                        class="rounded-lg bg-blue-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-blue-700">
                        <i class="fa-solid fa-sign-in-alt mr-1"></i> Enter Vessel
                    </button>
                </form>
            @endif

            <a href="{{ route('admin.vessels.edit', $vessel) }}"
                class="rounded-md bg-blue-600 px-6 py-3 font-medium text-white transition-colors hover:bg-blue-500">
                <i class="fa-solid fa-edit mr-2"></i>
                Edit Vessel
            </a>
        </div>

        {{-- Stats --}}
        <div class="mb-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div id="vessel-stats" class="grid grid-cols-1 gap-4 md:grid-cols-4 lg:col-span-3">
                <div class="rounded-lg border border-[#334e68] bg-[#243b53] p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-400">Total Users</p>
                            <p class="text-2xl font-semibold text-white">
                                {{ $vessel->boardings->count() }}
                            </p>
                        </div>
                        <i class="fa-solid fa-users text-xl text-blue-400"></i>
                    </div>
                </div>
                <div class="rounded-lg border border-[#334e68] bg-[#243b53] p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-400">Active Today</p>
                            <p class="text-2xl font-semibold text-white">
                                {{ $vessel->boardings->where('last_active_at', '>=', now()->startOfDay())->count() }}
                            </p>
                        </div>
                        <i class="fa-solid fa-user-check text-xl text-green-400"></i>
                    </div>
                </div>
                <div class="rounded-lg border border-[#334e68] bg-[#243b53] p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-400">S3 Usage</p>
                            <p class="text-2xl font-semibold text-white">
                                {{ number_format($vessel->data_usage_gb, 1) }}GB
                            </p>
                        </div>
                        <i class="fa-solid fa-database text-xl text-[#05A0D1]"></i>
                    </div>
                </div>
                <div class="rounded-lg border border-[#334e68] bg-[#243b53] p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-400">DB Usage</p>
                            <p class="text-2xl font-semibold text-white">
                                {{ number_format($vessel->data_usage_gb, 1) }}GB
                            </p>
                        </div>
                        <i class="fa-solid fa-database text-xl text-[#E39211]"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                {{-- Vessel Information --}}
                <div id="vessel-info-section" class="rounded-lg border border-[#334e68] bg-[#243b53] p-6">
                    <h2 class="mb-4 flex items-center text-lg font-semibold">
                        <i class="fa-solid fa-ship mr-2 text-blue-400"></i>
                        Vessel Information
                    </h2>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-400">Port of Registry</label>
                            <p class="text-white">{{ $vessel->registry_port }}</p>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-400">Build Year</label>
                            <p class="text-white">{{ $vessel->build_year }}</p>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-400">Vessel Make</label>
                            <p class="text-white">{{ $vessel->vessel_make }}</p>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-400">Official Number</label>
                            <p class="text-white">{{ $vessel->official_number }}</p>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-400">IMO Number</label>
                            <p class="text-white">{{ $vessel->imo_number }}</p>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-400">MMSI Number</label>
                            <p class="text-white">{{ $vessel->mmsi_number }}</p>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-400">Callsign</label>
                            <p class="text-white">{{ $vessel->callsign }}</p>
                        </div>
                    </div>
                </div>

                {{-- Technical Specs --}}
                <div id="technical-specs-display" class="rounded-lg border border-[#334e68] bg-[#243b53] p-6">
                    <h2 class="mb-4 flex items-center text-lg font-semibold">
                        <i class="fa-solid fa-cog mr-2 text-yellow-400"></i>
                        Technical Specifications
                    </h2>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-400">Length</label>
                            <p class="text-white">{{ $vessel->vessel_size }}m</p>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-400">LWL</label>
                            <p class="text-white">{{ number_format($vessel->vessel_lwl) }}</p>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-400">Width</label>
                            <p class="text-white">{{ $vessel->vessel_beam }}m</p>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-400">Draft</label>
                            <p class="text-white">{{ $vessel->vessel_draft }}m</p>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-400">Gross Tonnage</label>
                            <p class="text-white">{{ number_format($vessel->vessel_gt) }}</p>
                        </div>
                    </div>
                </div>

                {{-- Active Users Table --}}
                <div id="users-table-section" class="rounded-lg border border-[#334e68] bg-[#243b53] p-6">
                    <div class="mb-4 flex items-center justify-between">
                        <h2 class="flex items-center text-lg font-semibold text-white">
                            <i class="fa-solid fa-users mr-2 text-green-400"></i>
                            Active Users ({{ $vessel->boardings->count() }})
                        </h2>
                        <a href="{{ route('admin.vessels.add-user', $vessel) }}"
                            class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-blue-500">
                            <i class="fa-solid fa-user-plus mr-2"></i>
                            Add User
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-[#334e68]">
                                    <th class="py-2 text-left text-gray-400">Name</th>
                                    <th class="py-2 text-left text-gray-400">Department</th>
                                    <th class="py-2 text-left text-gray-400">Role</th>
                                    <th class="py-2 text-left text-gray-400">Permissions</th>
                                    <th class="py-2 text-left text-gray-400">Last Active</th>
                                    <th class="py-2 text-left text-gray-400">Status</th>
                                    <th class="py-2 text-left text-gray-400">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#334e68]">
                                @foreach ($vessel->boardings as $boarding)
                                    <tr>
                                        <td class="flex items-center py-3">
                                            <img src="{{ $boarding->user->profile_pic ? Storage::url($boarding->user->profile_pic) : asset('images/placeholders/user.png') }}"
                                                class="mr-3 h-8 w-8 rounded-full" alt="{{ $boarding->user->full_name }}">
                                            {{ $boarding->user->full_name }}
                                        </td>
                                        <td class="py-3">{{ $boarding->department ?? '—' }}</td>
                                        <td class="py-3">{{ $boarding->role ?? '—' }}</td>
                                        <td class="py-3">{{ $boarding->access_level ?? '—' }}</td>
                                        <td class="py-3">Placeholder</td>
                                        <td class="py-3">
                                            <span
                                                class="{{ $boarding->status === 'active' ? 'bg-green-600' : 'bg-yellow-600' }} rounded-full px-2 py-1 text-xs capitalize">
                                                {{ $boarding->status ?? '—' }}
                                            </span>
                                        </td>
                                        <td class="py-3">
                                            <a href="{{ route('admin.users.show', $boarding->user) }}"
                                                class="rounded bg-blue-600 px-3 py-1 text-xs text-white transition-colors hover:bg-blue-500">
                                                <i class="fa-solid fa-eye mr-1"></i>
                                                View User
                                            </a>
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
                <div id="owner-info-display" class="rounded-lg border border-[#334e68] bg-[#243b53] p-6">
                    <h2 class="mb-4 flex items-center text-lg font-semibold">
                        <i class="fa-solid fa-user mr-2 text-green-400"></i>
                        Owner Information
                    </h2>
                    @if ($vessel->owner)
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <img src="{{ $vessel->owner->profile_pic ? Storage::url($vessel->owner->profile_pic) : asset('images/placeholders/user.png') }}"
                                    class="mr-3 h-12 w-12 rounded-full" alt="{{ $vessel->owner->full_name }}">
                                <div>
                                    <p class="font-medium text-white">{{ $vessel->owner->full_name }}</p>
                                    <p class="text-sm text-gray-400">{{ $ownerBoarding->role ?? 'Owner' }}</p>
                                </div>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-400">Email</label>
                                <p class="text-sm text-white">{{ $vessel->owner->email }}</p>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-400">Phone</label>
                                <p class="text-sm text-white">{{ $vessel->owner->phone ?? '—' }}</p>
                            </div>
                            <button
                                class="mt-4 w-full rounded bg-blue-600 px-4 py-2 text-sm text-white transition-colors hover:bg-blue-500">
                                <i class="fa-solid fa-envelope mr-2"></i>
                                Contact Owner
                            </button>

                            <div class="mt-4 border-t border-[#334e68] pt-4">
                                <a href="{{ route('admin.vessels.transfer-ownership', $vessel) }}"
                                    class="block w-full rounded bg-yellow-600 px-4 py-2 text-center text-sm text-white transition-colors hover:bg-yellow-500">
                                    <i class="fa-solid fa-exchange-alt mr-2"></i>
                                    Transfer Ownership
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="py-4 text-center">
                            <p class="mb-3 text-gray-400">No owner assigned to this vessel</p>
                            <button
                                class="rounded bg-blue-600 px-4 py-2 text-sm text-white transition-colors hover:bg-blue-500">
                                <i class="fa-solid fa-user-plus mr-2"></i>
                                Assign Owner
                            </button>
                        </div>
                    @endif
                </div>

                {{-- Subscription Details --}}
                <div id="subscription-display" class="rounded-lg border border-[#334e68] bg-[#243b53] p-6">
                    <h2 class="mb-4 flex items-center text-lg font-semibold">
                        <i class="fa-solid fa-credit-card mr-2 text-purple-400"></i>
                        Subscription Details
                    </h2>
                    <div class="space-y-3">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-400">Plan</label>
                            <p class="text-white">BETA</p>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-400">Status</label>
                            <span class="rounded-full bg-green-600 px-2 py-1 text-xs">Active</span>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-400">Next Billing</label>
                            <p class="text-white">N/A</p>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-400">Max Users</label>
                            <p class="text-white">N/A</p>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-400">Monthly Cost</label>
                            <p class="font-semibold text-white">N/A</p>
                        </div>
                    </div>
                </div>

                {{-- System Health --}}
                <div id="system-health" class="rounded-lg border border-[#334e68] bg-[#243b53] p-6">
                    <h2 class="mb-4 flex items-center text-lg font-semibold">
                        <i class="fa-solid fa-heart-pulse mr-2 text-red-400"></i>
                        System Health
                    </h2>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-400">Connection Status</span>
                            <span class="rounded-full bg-green-600 px-2 py-1 text-xs">Connected</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-400">Data Sync</span>
                            <span class="rounded-full bg-green-600 px-2 py-1 text-xs">Synced</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-400">Last Backup</span>
                            <span class="text-sm text-gray-400">
                                ##
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-400">Storage Used</span>
                            <span class="text-sm text-gray-400">
                                ##GB
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Quick Actions --}}
                <div id="quick-actions" class="rounded-lg border border-[#334e68] bg-[#243b53] p-6">
                    <h2 class="mb-4 flex items-center text-lg font-semibold">
                        <i class="fa-solid fa-bolt mr-2 text-orange-400"></i>
                        Quick Actions
                    </h2>
                    <div class="space-y-2">
                        <button
                            class="w-full rounded bg-[#334e68] px-4 py-2 text-left text-sm text-white transition-colors hover:bg-[#3f5b7b]">
                            <i class="fa-solid fa-download mr-2"></i> Export Data
                        </button>
                        <button
                            class="w-full rounded bg-[#334e68] px-4 py-2 text-left text-sm text-white transition-colors hover:bg-[#3f5b7b]">
                            <i class="fa-solid fa-sync mr-2"></i> Force Sync
                        </button>
                        <button
                            class="w-full rounded bg-[#334e68] px-4 py-2 text-left text-sm text-white transition-colors hover:bg-[#3f5b7b]">
                            <i class="fa-solid fa-bell mr-2"></i> Send Alert
                        </button>
                        <button
                            class="w-full rounded bg-[#334e68] px-4 py-2 text-left text-sm text-white transition-colors hover:bg-[#3f5b7b]">
                            <i class="fa-solid fa-ban mr-2"></i> Suspend Access
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
