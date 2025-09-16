@extends('layouts.admin')

@section('title', 'Edit ' . $vessel->name)

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
                            <h1 class="text-xl font-semibold text-white">Edit Vessel</h1>
                            <p class="text-sm text-gray-400">{{ $vessel->name }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <button onclick="closeModal()" class="px-4 py-2 text-gray-400 hover:text-white transition-colors">
                            Cancel
                        </button>
                        <button type="submit" form="vessel-form" class="bg-blue-600 hover:bg-blue-500 px-6 py-2 rounded-md text-white font-medium transition-colors">
                            <i class="fa-solid fa-save mr-2"></i>
                            Update Vessel
                        </button>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <div class="flex-1 overflow-y-auto p-6">
                <form id="vessel-form" action="{{ route('admin.vessels.update', $vessel) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    @method('PUT')
                    
                    <!-- Basic Information -->
                    <div class="bg-dark-700 rounded-lg border border-dark-600 p-6">
                        <h2 class="text-lg font-semibold mb-4 flex items-center text-white">
                            <i class="fa-solid fa-ship mr-2 text-blue-400"></i>
                            Basic Information
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">
                                    Vessel Name <span class="text-red-400">*</span>
                                </label>
                                <input type="text" name="name" value="{{ old('name', $vessel->name) }}" required
                                    class="w-full bg-dark-600 border border-dark-500 rounded-md px-3 py-2 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('name')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">
                                    Vessel Type <span class="text-red-400">*</span>
                                </label>
                                <select name="type" required
                                    class="w-full bg-dark-600 border border-dark-500 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Select Type</option>
                                    <option value="MY" {{ old('type', $vessel->type) == 'MY' ? 'selected' : '' }}>Motor Yacht (MY)</option>
                                    <option value="SY" {{ old('type', $vessel->type) == 'SY' ? 'selected' : '' }}>Sailing Yacht (SY)</option>
                                    <option value="MV" {{ old('type', $vessel->type) == 'MV' ? 'selected' : '' }}>Motor Vessel (MV)</option>
                                    <option value="SV" {{ old('type', $vessel->type) == 'SV' ? 'selected' : '' }}>Sailing Vessel (SV)</option>
                                    <option value="FV" {{ old('type', $vessel->type) == 'FV' ? 'selected' : '' }}>Fishing Vessel (FV)</option>
                                    <option value="RV" {{ old('type', $vessel->type) == 'RV' ? 'selected' : '' }}>Research Vessel (RV)</option>
                                </select>
                                @error('type')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Flag</label>
                                <input type="text" name="flag" value="{{ old('flag', $vessel->flag) }}" placeholder="e.g., US, UK, CA"
                                    class="w-full bg-dark-600 border border-dark-500 rounded-md px-3 py-2 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('flag')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Registry Port</label>
                                <input type="text" name="registry_port" value="{{ old('registry_port', $vessel->registry_port) }}" placeholder="e.g., Miami, Southampton"
                                    class="w-full bg-dark-600 border border-dark-500 rounded-md px-3 py-2 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('registry_port')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Build Year</label>
                                <input type="text" name="build_year" value="{{ old('build_year', $vessel->build_year) }}" placeholder="e.g., 2020"
                                    class="w-full bg-dark-600 border border-dark-500 rounded-md px-3 py-2 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('build_year')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Vessel Make</label>
                                <input type="text" name="vessel_make" value="{{ old('vessel_make', $vessel->vessel_make) }}" placeholder="e.g., Azimut, Princess"
                                    class="w-full bg-dark-600 border border-dark-500 rounded-md px-3 py-2 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('vessel_make')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Technical Specifications -->
                    <div class="bg-dark-700 rounded-lg border border-dark-600 p-6">
                        <h2 class="text-lg font-semibold mb-4 flex items-center text-white">
                            <i class="fa-solid fa-cog mr-2 text-yellow-400"></i>
                            Technical Specifications
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Length (m)</label>
                                <input type="number" name="vessel_size" value="{{ old('vessel_size', $vessel->vessel_size) }}" min="1" step="0.1"
                                    class="w-full bg-dark-600 border border-dark-500 rounded-md px-3 py-2 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('vessel_size')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">LWL (m)</label>
                                <input type="number" name="vessel_lwl" value="{{ old('vessel_lwl', $vessel->vessel_lwl) }}" min="1" step="0.1"
                                    class="w-full bg-dark-600 border border-dark-500 rounded-md px-3 py-2 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('vessel_lwl')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Beam (m)</label>
                                <input type="number" name="vessel_beam" value="{{ old('vessel_beam', $vessel->vessel_beam) }}" min="1" step="0.1"
                                    class="w-full bg-dark-600 border border-dark-500 rounded-md px-3 py-2 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('vessel_beam')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Draft (m)</label>
                                <input type="number" name="vessel_draft" value="{{ old('vessel_draft', $vessel->vessel_draft) }}" min="0.1" step="0.1"
                                    class="w-full bg-dark-600 border border-dark-500 rounded-md px-3 py-2 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('vessel_draft')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Gross Tonnage</label>
                                <input type="number" name="vessel_gt" value="{{ old('vessel_gt', $vessel->vessel_gt) }}" min="1"
                                    class="w-full bg-dark-600 border border-dark-500 rounded-md px-3 py-2 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('vessel_gt')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">LOA (m)</label>
                                <input type="number" name="vessel_loa" value="{{ old('vessel_loa', $vessel->vessel_loa) }}" min="1" step="0.1"
                                    class="w-full bg-dark-600 border border-dark-500 rounded-md px-3 py-2 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('vessel_loa')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Official Numbers -->
                    <div class="bg-dark-700 rounded-lg border border-dark-600 p-6">
                        <h2 class="text-lg font-semibold mb-4 flex items-center text-white">
                            <i class="fa-solid fa-id-card mr-2 text-green-400"></i>
                            Official Numbers & Identification
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Official Number</label>
                                <input type="text" name="official_number" value="{{ old('official_number', $vessel->official_number) }}"
                                    class="w-full bg-dark-600 border border-dark-500 rounded-md px-3 py-2 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('official_number')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">MMSI Number</label>
                                <input type="text" name="mmsi_number" value="{{ old('mmsi_number', $vessel->mmsi_number) }}"
                                    class="w-full bg-dark-600 border border-dark-500 rounded-md px-3 py-2 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('mmsi_number')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">IMO Number</label>
                                <input type="text" name="imo_number" value="{{ old('imo_number', $vessel->imo_number) }}"
                                    class="w-full bg-dark-600 border border-dark-500 rounded-md px-3 py-2 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('imo_number')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Callsign</label>
                                <input type="text" name="callsign" value="{{ old('callsign', $vessel->callsign) }}"
                                    class="w-full bg-dark-600 border border-dark-500 rounded-md px-3 py-2 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('callsign')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="bg-dark-700 rounded-lg border border-dark-600 p-6">
                        <h2 class="text-lg font-semibold mb-4 flex items-center text-white">
                            <i class="fa-solid fa-address-book mr-2 text-purple-400"></i>
                            Contact Information
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Vessel Phone</label>
                                <input type="text" name="vessel_phone" value="{{ old('vessel_phone', $vessel->vessel_phone) }}"
                                    class="w-full bg-dark-600 border border-dark-500 rounded-md px-3 py-2 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('vessel_phone')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Vessel Email</label>
                                <input type="email" name="vessel_email" value="{{ old('vessel_email', $vessel->vessel_email) }}"
                                    class="w-full bg-dark-600 border border-dark-500 rounded-md px-3 py-2 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('vessel_email')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">DPA Name</label>
                                <input type="text" name="dpa_name" value="{{ old('dpa_name', $vessel->dpa_name) }}"
                                    class="w-full bg-dark-600 border border-dark-500 rounded-md px-3 py-2 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('dpa_name')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">DPA Phone</label>
                                <input type="text" name="dpa_phone" value="{{ old('dpa_phone', $vessel->dpa_phone) }}"
                                    class="w-full bg-dark-600 border border-dark-500 rounded-md px-3 py-2 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('dpa_phone')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">DPA Email</label>
                                <input type="email" name="dpa_email" value="{{ old('dpa_email', $vessel->dpa_email) }}"
                                    class="w-full bg-dark-600 border border-dark-500 rounded-md px-3 py-2 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('dpa_email')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Account Owner</label>
                                <select name="account_owner"
                                    class="w-full bg-dark-600 border border-dark-500 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Select Account Owner</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('account_owner', $vessel->account_owner) == $user->id ? 'selected' : '' }}>
                                            {{ $user->full_name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('account_owner')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Media -->
                    <div class="bg-dark-700 rounded-lg border border-dark-600 p-6">
                        <h2 class="text-lg font-semibold mb-4 flex items-center text-white">
                            <i class="fa-solid fa-image mr-2 text-pink-400"></i>
                            Media
                        </h2>
                        <div class="space-y-4">
                            @if($vessel->hero_photo)
                                <div class="bg-dark-600 rounded-lg p-4">
                                    <label class="block text-sm font-medium text-gray-300 mb-2">Current Hero Photo</label>
                                    <div class="flex items-center space-x-4">
                                        <img src="{{ Storage::url($vessel->hero_photo) }}" alt="Current hero photo" 
                                             class="w-20 h-20 object-cover rounded-lg">
                                        <div class="text-sm text-gray-400">
                                            <p>Current photo: {{ basename($vessel->hero_photo) }}</p>
                                            <p class="text-xs">Upload a new photo to replace this one</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">
                                    {{ $vessel->hero_photo ? 'New Hero Photo' : 'Hero Photo' }}
                                </label>
                                <input type="file" name="hero_photo" accept="image/*"
                                    class="w-full bg-dark-600 border border-dark-500 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-600 file:text-white hover:file:bg-blue-500 file:cursor-pointer">
                                <p class="text-xs text-gray-400 mt-1">
                                    <i class="fa-solid fa-info-circle mr-1"></i>
                                    Accepted formats: JPEG, PNG, JPG, GIF. Max size: 2MB.
                                    @if($vessel->hero_photo)
                                        Leave empty to keep current photo.
                                    @endif
                                </p>
                                @error('hero_photo')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
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
