@extends('layouts.admin')

@section('title', 'Edit Staff Member - ' . ($user->full_name ?? 'Unknown Staff Member'))

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.staff.show', $user) }}" class="text-accent-primary hover:text-blue-400 transition-colors duration-200">
                <i class="fa-solid fa-arrow-left mr-2"></i>Back to Staff Member
            </a>
            <div>
                <h1 class="text-2xl font-bold text-white">Edit Staff Member</h1>
                <p class="text-gray-400 mt-1">{{ $user->email ?? 'No email' }}</p>
            </div>
        </div>
    </div>

    <div class="max-w-2xl">
        <div class="bg-dark-800 rounded-lg border border-dark-600">
            <div class="px-6 py-4 border-b border-dark-600">
                <h3 class="text-lg font-semibold text-white">Staff Information</h3>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('admin.staff.update', $user) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- Name Fields --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-300 mb-2">First Name</label>
                            <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" 
                                   class="w-full bg-dark-700 border border-dark-600 text-white rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-accent-primary"
                                   required>
                            @error('first_name')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-300 mb-2">Last Name</label>
                            <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" 
                                   class="w-full bg-dark-700 border border-dark-600 text-white rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-accent-primary"
                                   required>
                            @error('last_name')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" 
                               class="w-full bg-dark-700 border border-dark-600 text-white rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-accent-primary"
                               required>
                        @error('email')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-300 mb-2">Phone (Optional)</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" 
                               class="w-full bg-dark-700 border border-dark-600 text-white rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-accent-primary">
                        @error('phone')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- System Role --}}
                    <div>
                        <label for="system_role" class="block text-sm font-medium text-gray-300 mb-2">System Role</label>
                        <select id="system_role" name="system_role" 
                                class="w-full bg-dark-700 border border-dark-600 text-white rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-accent-primary"
                                required>
                            <option value="superadmin" {{ old('system_role', $user->system_role) === 'superadmin' ? 'selected' : '' }}>
                                Super Administrator
                            </option>
                            <option value="staff" {{ old('system_role', $user->system_role) === 'staff' ? 'selected' : '' }}>
                                Staff Member
                            </option>
                            <option value="dev" {{ old('system_role', $user->system_role) === 'dev' ? 'selected' : '' }}>
                                Developer
                            </option>
                        </select>
                        @error('system_role')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        
                        {{-- Role Descriptions --}}
                        <div class="mt-3 space-y-2">
                            <div class="text-xs text-gray-400">
                                <strong>Super Administrator:</strong> Full system access, user management, data management
                            </div>
                            <div class="text-xs text-gray-400">
                                <strong>Staff Member:</strong> Vessel management, maintenance tools, reports & analytics
                            </div>
                            <div class="text-xs text-gray-400">
                                <strong>Developer:</strong> System development, debugging, technical operations
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex items-center justify-end space-x-3 pt-6 border-t border-dark-600">
                        <a href="{{ route('admin.staff.show', $user) }}" 
                           class="bg-dark-700 hover:bg-dark-600 text-white px-4 py-2 rounded-md transition-colors duration-200">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="bg-accent-primary hover:bg-blue-600 text-white px-6 py-2 rounded-md transition-colors duration-200">
                            Update Staff Member
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
