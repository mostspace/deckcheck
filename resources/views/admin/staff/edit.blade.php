@extends('layouts.admin')

@section('title', 'Edit Staff Member - ' . ($user->full_name ?? 'Unknown Staff Member'))

@section('content')
    <div class="space-y-6">
        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.staff.show', $user) }}"
                    class="text-accent-primary transition-colors duration-200 hover:text-blue-400">
                    <i class="fa-solid fa-arrow-left mr-2"></i>Back to Staff Member
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-white">Edit Staff Member</h1>
                    <p class="mt-1 text-gray-400">{{ $user->email ?? 'No email' }}</p>
                </div>
            </div>
        </div>

        <div class="max-w-2xl">
            <div class="bg-dark-800 border-dark-600 rounded-lg border">
                <div class="border-dark-600 border-b px-6 py-4">
                    <h3 class="text-lg font-semibold text-white">Staff Information</h3>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.staff.update', $user) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        {{-- Name Fields --}}
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label for="first_name" class="mb-2 block text-sm font-medium text-gray-300">First
                                    Name</label>
                                <input type="text" id="first_name" name="first_name"
                                    value="{{ old('first_name', $user->first_name) }}"
                                    class="bg-dark-700 border-dark-600 focus:ring-accent-primary w-full rounded-md border px-3 py-2 text-white focus:outline-none focus:ring-2"
                                    required>
                                @error('first_name')
                                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="last_name" class="mb-2 block text-sm font-medium text-gray-300">Last
                                    Name</label>
                                <input type="text" id="last_name" name="last_name"
                                    value="{{ old('last_name', $user->last_name) }}"
                                    class="bg-dark-700 border-dark-600 focus:ring-accent-primary w-full rounded-md border px-3 py-2 text-white focus:outline-none focus:ring-2"
                                    required>
                                @error('last_name')
                                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="email" class="mb-2 block text-sm font-medium text-gray-300">Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                                class="bg-dark-700 border-dark-600 focus:ring-accent-primary w-full rounded-md border px-3 py-2 text-white focus:outline-none focus:ring-2"
                                required>
                            @error('email')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Phone --}}
                        <div>
                            <label for="phone" class="mb-2 block text-sm font-medium text-gray-300">Phone
                                (Optional)</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                                class="bg-dark-700 border-dark-600 focus:ring-accent-primary w-full rounded-md border px-3 py-2 text-white focus:outline-none focus:ring-2">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- System Role --}}
                        <div>
                            <label for="system_role" class="mb-2 block text-sm font-medium text-gray-300">System
                                Role</label>
                            <select id="system_role" name="system_role"
                                class="bg-dark-700 border-dark-600 focus:ring-accent-primary w-full rounded-md border px-3 py-2 text-white focus:outline-none focus:ring-2"
                                required>
                                <option value="superadmin"
                                    {{ old('system_role', $user->system_role) === 'superadmin' ? 'selected' : '' }}>
                                    Super Administrator
                                </option>
                                <option value="staff"
                                    {{ old('system_role', $user->system_role) === 'staff' ? 'selected' : '' }}>
                                    Staff Member
                                </option>
                                <option value="dev"
                                    {{ old('system_role', $user->system_role) === 'dev' ? 'selected' : '' }}>
                                    Developer
                                </option>
                            </select>
                            @error('system_role')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror

                            {{-- Role Descriptions --}}
                            <div class="mt-3 space-y-2">
                                <div class="text-xs text-gray-400">
                                    <strong>Super Administrator:</strong> Full system access, user management, data
                                    management
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
                        <div class="border-dark-600 flex items-center justify-end space-x-3 border-t pt-6">
                            <a href="{{ route('admin.staff.show', $user) }}"
                                class="bg-dark-700 hover:bg-dark-600 rounded-md px-4 py-2 text-white transition-colors duration-200">
                                Cancel
                            </a>
                            <button type="submit"
                                class="bg-accent-primary rounded-md px-6 py-2 text-white transition-colors duration-200 hover:bg-blue-600">
                                Update Staff Member
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
