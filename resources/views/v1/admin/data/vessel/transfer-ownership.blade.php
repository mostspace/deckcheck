@extends('layouts.admin')

@section('title', 'Transfer Ownership - ' . $vessel->name)

@section('content')
<div class="fixed inset-0 bg-black bg-opacity-50 z-40" id="modal-backdrop"></div>

<div class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex min-h-full">
        <!-- Slide-out modal -->
        <div class="w-full bg-dark-800 shadow-xl transform transition-transform duration-300 ease-in-out" id="modal-content">
            <!-- Header -->
            <div class="bg-dark-700 border-b border-dark-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <button onclick="closeModal()" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fa-solid fa-arrow-left text-xl"></i>
                        </button>
                        <div>
                            <h1 class="text-xl font-semibold text-white">Transfer Vessel Ownership</h1>
                            <p class="text-sm text-gray-400">{{ $vessel->name }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <button onclick="closeModal()" class="px-4 py-2 text-gray-400 hover:text-white transition-colors">
                            Cancel
                        </button>
                        <button type="submit" form="transfer-ownership-form" class="bg-yellow-600 hover:bg-yellow-500 px-6 py-2 rounded-md text-white font-medium transition-colors">
                            <i class="fa-solid fa-exchange-alt mr-2"></i>
                            Transfer Ownership
                        </button>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <div class="flex-1 overflow-y-auto p-6">
                <form id="transfer-ownership-form" action="{{ route('admin.vessels.process-ownership-transfer', $vessel) }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Current Owner Information -->
                    <div class="bg-dark-700 rounded-lg border border-dark-600 p-6">
                        <h2 class="text-lg font-semibold mb-4 flex items-center text-white">
                            <i class="fa-solid fa-user mr-2 text-blue-400"></i>
                            Current Owner
                        </h2>
                        @if($vessel->owner)
                            <div class="flex items-center space-x-4 p-4 bg-dark-600 rounded-lg">
                                <img src="{{ $vessel->owner->profile_pic ? Storage::url($vessel->owner->profile_pic) : asset('images/placeholders/user.png') }}" 
                                     class="w-12 h-12 rounded-full" alt="{{ $vessel->owner->full_name }}">
                                <div>
                                    <p class="font-medium text-white">{{ $vessel->owner->full_name }}</p>
                                    <p class="text-sm text-gray-400">{{ $vessel->owner->email }}</p>
                                    <p class="text-xs text-yellow-400">Current Account Owner</p>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-4 text-gray-400">
                                <i class="fa-solid fa-exclamation-triangle text-2xl mb-2"></i>
                                <p>No current owner assigned</p>
                            </div>
                        @endif
                    </div>

                    <!-- New Owner Selection -->
                    <div class="bg-dark-700 rounded-lg border border-dark-600 p-6">
                        <h2 class="text-lg font-semibold mb-4 flex items-center text-white">
                            <i class="fa-solid fa-user-plus mr-2 text-green-400"></i>
                            Select New Owner
                        </h2>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">
                                New Owner <span class="text-red-400">*</span>
                            </label>
                            <select name="new_owner_id" required
                                class="w-full bg-dark-600 border border-dark-500 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                                <option value="">Select a new owner</option>
                                @foreach($eligibleUsers as $boarding)
                                    <option value="{{ $boarding->user->id }}" {{ old('new_owner_id') == $boarding->user->id ? 'selected' : '' }}>
                                        {{ $boarding->user->full_name }} ({{ $boarding->user->email }}) - {{ ucfirst($boarding->access_level) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('new_owner_id')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            @if($eligibleUsers->isEmpty())
                                <p class="text-yellow-400 text-sm mt-1">
                                    <i class="fa-solid fa-exclamation-triangle mr-1"></i>
                                    No eligible users found. Users must have an active boarding on this vessel.
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- What Will Happen -->
                    <div class="bg-dark-700 rounded-lg border border-dark-600 p-6">
                        <h2 class="text-lg font-semibold mb-4 flex items-center text-white">
                            <i class="fa-solid fa-info-circle mr-2 text-blue-400"></i>
                            What Will Happen
                        </h2>
                        <div class="space-y-3 text-sm text-gray-300">
                            <div class="flex items-start space-x-3">
                                <i class="fa-solid fa-check-circle text-green-400 mt-0.5"></i>
                                <div>
                                    <span class="font-medium text-white">New Owner:</span>
                                    <span class="text-green-400">Access level changed to "owner"</span>
                                </div>
                            </div>
                            @if($vessel->owner)
                                <div class="flex items-start space-x-3">
                                    <i class="fa-solid fa-arrow-down text-yellow-400 mt-0.5"></i>
                                    <div>
                                        <span class="font-medium text-white">Current Owner:</span>
                                        <span class="text-yellow-400">Access level changed to "admin"</span>
                                    </div>
                                </div>
                            @endif
                            <div class="flex items-start space-x-3">
                                <i class="fa-solid fa-database text-blue-400 mt-0.5"></i>
                                <div>
                                    <span class="font-medium text-white">Vessel Record:</span>
                                    <span class="text-blue-400">account_owner field updated</span>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <i class="fa-solid fa-shield-alt text-purple-400 mt-0.5"></i>
                                <div>
                                    <span class="font-medium text-white">Billing Control:</span>
                                    <span class="text-purple-400">Transferred to new owner</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Important Notes -->
                    <div class="bg-yellow-900/20 border border-yellow-600/30 rounded-lg p-4">
                        <div class="flex items-start space-x-3">
                            <i class="fa-solid fa-exclamation-triangle text-yellow-400 mt-0.5"></i>
                            <div class="text-sm text-yellow-200">
                                <p class="font-medium mb-2">Important Notes:</p>
                                <ul class="space-y-1 text-yellow-100">
                                    <li>• This action cannot be undone automatically</li>
                                    <li>• The new owner will have full billing control</li>
                                    <li>• Previous owner retains admin access to the vessel</li>
                                    <li>• All existing vessel data and settings remain intact</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function closeModal() {
    window.location.href = '{{ route("admin.vessels.show", $vessel) }}';
}

// Close modal on backdrop click
document.getElementById('modal-backdrop').addEventListener('click', closeModal);

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});

// Confirm before submitting
document.getElementById('transfer-ownership-form').addEventListener('submit', function(e) {
    if (!confirm('Are you sure you want to transfer ownership of this vessel? This action cannot be undone.')) {
        e.preventDefault();
    }
});
</script>
@endsection
