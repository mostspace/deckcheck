@extends('layouts.admin')

@section('title', 'Add User to ' . $vessel->name)

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
                            <h1 class="text-xl font-semibold text-white">Add User to Vessel</h1>
                            <p class="text-sm text-gray-400">{{ $vessel->name }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <button onclick="closeModal()" class="px-4 py-2 text-gray-400 hover:text-white transition-colors">
                            Cancel
                        </button>
                        <button type="submit" form="add-user-form" class="bg-blue-600 hover:bg-blue-500 px-6 py-2 rounded-md text-white font-medium transition-colors">
                            <i class="fa-solid fa-user-plus mr-2"></i>
                            Add User
                        </button>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <div class="flex-1 overflow-y-auto p-6">
                <form id="add-user-form" action="{{ route('admin.vessels.store-user', $vessel) }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- User Selection -->
                    <div class="bg-dark-700 rounded-lg border border-dark-600 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold flex items-center text-white">
                                <i class="fa-solid fa-user mr-2 text-blue-400"></i>
                                Select User
                            </h2>
                            <span class="text-sm text-gray-400">{{ $availableUsers->count() }} users available</span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">
                                User <span class="text-red-400">*</span>
                            </label>
                            <select name="user_id" required
                                class="w-full bg-dark-600 border border-dark-500 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select a user to add</option>
                                @foreach($availableUsers as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->full_name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            @if($availableUsers->isEmpty())
                                <p class="text-yellow-400 text-sm mt-1">
                                    <i class="fa-solid fa-exclamation-triangle mr-1"></i>
                                    All users are already associated with this vessel.
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Access Configuration -->
                    <div class="bg-dark-700 rounded-lg border border-dark-600 p-6">
                        <h2 class="text-lg font-semibold mb-4 flex items-center text-white">
                            <i class="fa-solid fa-shield-alt mr-2 text-green-400"></i>
                            Access Configuration
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">
                                    Access Level <span class="text-red-400">*</span>
                                </label>
                                <select name="access_level" required
                                    class="w-full bg-dark-600 border border-dark-500 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Select Access Level</option>
                                    <option value="admin" {{ old('access_level') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="crew" {{ old('access_level') == 'crew' ? 'selected' : '' }}>Crew</option>
                                    <option value="viewer" {{ old('access_level') == 'viewer' ? 'selected' : '' }}>Viewer</option>
                                </select>
                                @error('access_level')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">
                                    Department <span class="text-red-400">*</span>
                                </label>
                                <select name="department" required
                                    class="w-full bg-dark-600 border border-dark-500 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Select Department</option>
                                    <option value="bridge" {{ old('department') == 'bridge' ? 'selected' : '' }}>Bridge</option>
                                    <option value="interior" {{ old('department') == 'interior' ? 'selected' : '' }}>Interior</option>
                                    <option value="exterior" {{ old('exterior') == 'exterior' ? 'selected' : '' }}>Exterior</option>
                                    <option value="galley" {{ old('department') == 'galley' ? 'selected' : '' }}>Galley</option>
                                    <option value="engineering" {{ old('department') == 'engineering' ? 'selected' : '' }}>Engineering</option>
                                    <option value="management" {{ old('department') == 'management' ? 'selected' : '' }}>Management</option>
                                    <option value="owner" {{ old('department') == 'owner' ? 'selected' : '' }}>Owner</option>
                                </select>
                                @error('department')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">
                                    Role <span class="text-red-400">*</span>
                                </label>
                                <input type="text" name="role" value="{{ old('role') }}" placeholder="e.g., Captain, Chief Engineer, Steward"
                                    class="w-full bg-dark-600 border border-dark-500 rounded-md px-3 py-2 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('role')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">
                                    Crew Number <span class="text-gray-400 text-xs">(Optional)</span>
                                </label>
                                <input type="number" name="crew_number" value="{{ old('crew_number') }}" min="1" placeholder="e.g., 1, 2, 3"
                                    class="w-full bg-dark-600 border border-dark-500 rounded-md px-3 py-2 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('crew_number')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Summary -->
                    <div class="bg-dark-700 rounded-lg border border-dark-600 p-6">
                        <h2 class="text-lg font-semibold mb-4 flex items-center text-white">
                            <i class="fa-solid fa-info-circle mr-2 text-blue-400"></i>
                            Summary
                        </h2>
                        <div class="space-y-3 text-sm text-gray-300">
                            <div class="flex justify-between">
                                <span>Status:</span>
                                <span class="text-green-400 font-medium">Active</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Crew Member:</span>
                                <span class="text-blue-400 font-medium">Yes</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Primary Vessel:</span>
                                <span class="text-yellow-400 font-medium">Auto-determined</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Join Date:</span>
                                <span class="text-white font-medium">{{ now()->format('M j, Y') }}</span>
                            </div>
                        </div>
                        
                        <div class="mt-4 p-3 bg-blue-900/20 border border-blue-600/30 rounded-lg">
                            <div class="flex items-start space-x-3">
                                <i class="fa-solid fa-lightbulb text-blue-400 mt-0.5"></i>
                                <div class="text-sm text-blue-200">
                                    <p class="font-medium mb-1">Primary Vessel Logic:</p>
                                    <ul class="space-y-1 text-blue-100 text-xs">
                                        <li>• <strong>If user has no active vessels:</strong> This becomes their primary vessel</li>
                                        <li>• <strong>If user has existing vessels:</strong> This vessel is added as secondary</li>
                                        <li>• <strong>Primary vessel</strong> determines which account they see by default</li>
                                    </ul>
                                </div>
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
</script>
@endsection
