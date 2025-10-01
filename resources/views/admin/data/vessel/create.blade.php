@extends('layouts.admin')

@section('title', 'Create Vessel')

@section('content')
    <div class="fixed inset-0 z-40 bg-black bg-opacity-50" id="modal-backdrop"></div>

    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full">
            <!-- Slide-out modal -->
            <div class="bg-dark-800 w-full transform shadow-xl transition-transform duration-300 ease-in-out"
                id="modal-content">
                <!-- Header -->
                <div class="bg-dark-700 border-dark-600 border-b px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <button onclick="closeModal()" class="text-gray-400 transition-colors hover:text-white">
                                <i class="fa-solid fa-arrow-left text-xl"></i>
                            </button>
                            <div>
                                <h1 class="text-xl font-semibold text-white">Create New Vessel</h1>
                                <p class="text-sm text-gray-400">Add a new vessel to the system</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <button onclick="closeModal()"
                                class="px-4 py-2 text-gray-400 transition-colors hover:text-white">
                                Cancel
                            </button>
                            <button type="submit" form="vessel-form"
                                class="rounded-md bg-blue-600 px-6 py-2 font-medium text-white transition-colors hover:bg-blue-500">
                                <i class="fa-solid fa-save mr-2"></i>
                                Create Vessel
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Form Content -->
                <div class="flex-1 overflow-y-auto p-6">
                    <form id="vessel-form" action="{{ route('admin.vessels.store') }}" method="POST"
                        enctype="multipart/form-data" class="space-y-8">
                        @csrf

                        <!-- Basic Information -->
                        <div class="bg-dark-700 border-dark-600 rounded-lg border p-6">
                            <h2 class="mb-4 flex items-center text-lg font-semibold text-white">
                                <i class="fa-solid fa-ship mr-2 text-blue-400"></i>
                                Basic Information
                            </h2>
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-300">
                                        Vessel Name <span class="text-red-400">*</span>
                                    </label>
                                    <input type="text" name="name" value="{{ old('name') }}" required
                                        class="bg-dark-600 border-dark-500 w-full rounded-md border px-3 py-2 text-white placeholder-gray-400 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-300">
                                        Vessel Type <span class="text-red-400">*</span>
                                    </label>
                                    <select name="type" required
                                        class="bg-dark-600 border-dark-500 w-full rounded-md border px-3 py-2 text-white focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">Select Type</option>
                                        <option value="MY" {{ old('type') == 'MY' ? 'selected' : '' }}>Motor Yacht (MY)
                                        </option>
                                        <option value="SY" {{ old('type') == 'SY' ? 'selected' : '' }}>Sailing Yacht
                                            (SY)</option>
                                        <option value="MV" {{ old('type') == 'MV' ? 'selected' : '' }}>Motor Vessel (MV)
                                        </option>
                                        <option value="SV" {{ old('type') == 'SV' ? 'selected' : '' }}>Sailing Vessel
                                            (SV)</option>
                                        <option value="FV" {{ old('type') == 'FV' ? 'selected' : '' }}>Fishing Vessel
                                            (FV)</option>
                                        <option value="RV" {{ old('type') == 'RV' ? 'selected' : '' }}>Research Vessel
                                            (RV)</option>
                                    </select>
                                    @error('type')
                                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-300">Flag</label>
                                    <input type="text" name="flag" value="{{ old('flag') }}"
                                        placeholder="e.g., US, UK, CA"
                                        class="bg-dark-600 border-dark-500 w-full rounded-md border px-3 py-2 text-white placeholder-gray-400 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @error('flag')
                                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-300">Registry Port</label>
                                    <input type="text" name="registry_port" value="{{ old('registry_port') }}"
                                        placeholder="e.g., Miami, Southampton"
                                        class="bg-dark-600 border-dark-500 w-full rounded-md border px-3 py-2 text-white placeholder-gray-400 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @error('registry_port')
                                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-300">Build Year</label>
                                    <input type="text" name="build_year" value="{{ old('build_year') }}"
                                        placeholder="e.g., 2020"
                                        class="bg-dark-600 border-dark-500 w-full rounded-md border px-3 py-2 text-white placeholder-gray-400 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @error('build_year')
                                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-300">Vessel Make</label>
                                    <input type="text" name="vessel_make" value="{{ old('vessel_make') }}"
                                        placeholder="e.g., Azimut, Princess"
                                        class="bg-dark-600 border-dark-500 w-full rounded-md border px-3 py-2 text-white placeholder-gray-400 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @error('vessel_make')
                                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Technical Specifications -->
                        <div class="bg-dark-700 border-dark-600 rounded-lg border p-6">
                            <h2 class="mb-4 flex items-center text-lg font-semibold text-white">
                                <i class="fa-solid fa-cog mr-2 text-yellow-400"></i>
                                Technical Specifications
                            </h2>
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-300">Length (m)</label>
                                    <input type="number" name="vessel_size" value="{{ old('vessel_size') }}"
                                        min="1" step="0.1"
                                        class="bg-dark-600 border-dark-500 w-full rounded-md border px-3 py-2 text-white placeholder-gray-400 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @error('vessel_size')
                                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-300">LWL (m)</label>
                                    <input type="number" name="vessel_lwl" value="{{ old('vessel_lwl') }}" min="1"
                                        step="0.1"
                                        class="bg-dark-600 border-dark-500 w-full rounded-md border px-3 py-2 text-white placeholder-gray-400 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @error('vessel_lwl')
                                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-300">Beam (m)</label>
                                    <input type="number" name="vessel_beam" value="{{ old('vessel_beam') }}"
                                        min="1" step="0.1"
                                        class="bg-dark-600 border-dark-500 w-full rounded-md border px-3 py-2 text-white placeholder-gray-400 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @error('vessel_beam')
                                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-300">Draft (m)</label>
                                    <input type="number" name="vessel_draft" value="{{ old('vessel_draft') }}"
                                        min="0.1" step="0.1"
                                        class="bg-dark-600 border-dark-500 w-full rounded-md border px-3 py-2 text-white placeholder-gray-400 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @error('vessel_draft')
                                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-300">Gross Tonnage</label>
                                    <input type="number" name="vessel_gt" value="{{ old('vessel_gt') }}"
                                        min="1"
                                        class="bg-dark-600 border-dark-500 w-full rounded-md border px-3 py-2 text-white placeholder-gray-400 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @error('vessel_gt')
                                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-300">LOA (m)</label>
                                    <input type="number" name="vessel_loa" value="{{ old('vessel_loa') }}"
                                        min="1" step="0.1"
                                        class="bg-dark-600 border-dark-500 w-full rounded-md border px-3 py-2 text-white placeholder-gray-400 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @error('vessel_loa')
                                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Official Numbers -->
                        <div class="bg-dark-700 border-dark-600 rounded-lg border p-6">
                            <h2 class="mb-4 flex items-center text-lg font-semibold text-white">
                                <i class="fa-solid fa-id-card mr-2 text-green-400"></i>
                                Official Numbers & Identification
                            </h2>
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-300">Official Number</label>
                                    <input type="text" name="official_number" value="{{ old('official_number') }}"
                                        class="bg-dark-600 border-dark-500 w-full rounded-md border px-3 py-2 text-white placeholder-gray-400 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @error('official_number')
                                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-300">MMSI Number</label>
                                    <input type="text" name="mmsi_number" value="{{ old('mmsi_number') }}"
                                        class="bg-dark-600 border-dark-500 w-full rounded-md border px-3 py-2 text-white placeholder-gray-400 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @error('mmsi_number')
                                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-300">IMO Number</label>
                                    <input type="text" name="imo_number" value="{{ old('imo_number') }}"
                                        class="bg-dark-600 border-dark-500 w-full rounded-md border px-3 py-2 text-white placeholder-gray-400 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @error('imo_number')
                                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-300">Callsign</label>
                                    <input type="text" name="callsign" value="{{ old('callsign') }}"
                                        class="bg-dark-600 border-dark-500 w-full rounded-md border px-3 py-2 text-white placeholder-gray-400 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @error('callsign')
                                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="bg-dark-700 border-dark-600 rounded-lg border p-6">
                            <h2 class="mb-4 flex items-center text-lg font-semibold text-white">
                                <i class="fa-solid fa-address-book mr-2 text-purple-400"></i>
                                Contact Information
                            </h2>
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-300">Vessel Phone</label>
                                    <input type="text" name="vessel_phone" value="{{ old('vessel_phone') }}"
                                        class="bg-dark-600 border-dark-500 w-full rounded-md border px-3 py-2 text-white placeholder-gray-400 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @error('vessel_phone')
                                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-300">Vessel Email</label>
                                    <input type="email" name="vessel_email" value="{{ old('vessel_email') }}"
                                        class="bg-dark-600 border-dark-500 w-full rounded-md border px-3 py-2 text-white placeholder-gray-400 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @error('vessel_email')
                                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-300">DPA Name</label>
                                    <input type="text" name="dpa_name" value="{{ old('dpa_name') }}"
                                        class="bg-dark-600 border-dark-500 w-full rounded-md border px-3 py-2 text-white placeholder-gray-400 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @error('dpa_name')
                                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-300">DPA Phone</label>
                                    <input type="text" name="dpa_phone" value="{{ old('dpa_phone') }}"
                                        class="bg-dark-600 border-dark-500 w-full rounded-md border px-3 py-2 text-white placeholder-gray-400 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @error('dpa_phone')
                                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-300">DPA Email</label>
                                    <input type="email" name="dpa_email" value="{{ old('dpa_email') }}"
                                        class="bg-dark-600 border-dark-500 w-full rounded-md border px-3 py-2 text-white placeholder-gray-400 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @error('dpa_email')
                                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-300">
                                        Account Owner <span class="text-xs text-blue-400">(Billing Control)</span>
                                    </label>
                                    <select name="account_owner"
                                        class="bg-dark-600 border-dark-500 w-full rounded-md border px-3 py-2 text-white focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">Leave blank to assign yourself as owner</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}"
                                                {{ old('account_owner') == $user->id ? 'selected' : '' }}>
                                                {{ $user->full_name }} ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="mt-1 text-xs text-gray-400">
                                        <i class="fa-solid fa-info-circle mr-1"></i>
                                        If no owner is selected, you will be assigned as the account owner for billing
                                        purposes.
                                    </p>
                                    @error('account_owner')
                                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Media -->
                        <div class="bg-dark-700 border-dark-600 rounded-lg border p-6">
                            <h2 class="mb-4 flex items-center text-lg font-semibold text-white">
                                <i class="fa-solid fa-image mr-2 text-pink-400"></i>
                                Media
                            </h2>
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-300">Hero Photo</label>
                                <input type="file" name="hero_photo" accept="image/*"
                                    class="bg-dark-600 border-dark-500 w-full rounded-md border px-3 py-2 text-white file:mr-4 file:cursor-pointer file:rounded-md file:border-0 file:bg-blue-600 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-blue-500 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <p class="mt-1 text-xs text-gray-400">
                                    <i class="fa-solid fa-info-circle mr-1"></i>
                                    Accepted formats: JPEG, PNG, JPG, GIF. Max size: 2MB.
                                </p>
                                @error('hero_photo')
                                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function closeModal() {
            window.location.href = '{{ route('admin.vessels.index') }}';
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
