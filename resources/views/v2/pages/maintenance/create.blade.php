@extends('v2.layouts.app')

@section('title', 'Create Maintenance Category')

@section('content')

    @include('v2.components.maintenance.header', [
        'activeTab' => 'index',
        'breadcrumbs' => [
            ['label' => 'Maintenance', 'icon' => asset('assets/media/icons/sidebar-solid-wrench-scredriver.svg')],
            ['label' => 'Index', 'url' => route('maintenance.index')],
            ['label' => 'Create Category']
        ]
    ])

    <div class="flex flex-col gap-4 sm:gap-8 px-3 sm:px-6 lg:px-8 py-4 sm:py-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-[#0f1728]">Create Maintenance Category</h1>
                <p class="text-[#475466]">Add a new maintenance category to organize equipment and intervals.</p>
            </div>
        </div>

        {{-- Form --}}
        <div class="bg-white border rounded-lg shadow">
            <form method="POST" action="{{ route('maintenance.store') }}" class="p-6">
                @csrf
                
                <div class="grid grid-cols-1 gap-6">
                    {{-- Category Name --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Category Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                               placeholder="Enter category name"
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Category Type --}}
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                            Category Type <span class="text-red-500">*</span>
                        </label>
                        <select id="type" 
                                name="type" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('type') border-red-500 @enderror"
                                required>
                            <option value="">Select a type</option>
                            @foreach($types as $type)
                                <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Icon Selection --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Category Icon <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-6 gap-3">
                            @foreach($icons as $icon)
                                <label class="flex flex-col items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 {{ old('icon') == $icon ? 'border-blue-500 bg-blue-50' : 'border-gray-300' }}">
                                    <input type="radio" 
                                           name="icon" 
                                           value="{{ $icon }}" 
                                           class="sr-only"
                                           {{ old('icon') == $icon ? 'checked' : '' }}>
                                    <i class="fa-solid {{ $icon }} text-2xl text-gray-600 mb-2"></i>
                                    <span class="text-xs text-gray-600 text-center">{{ $icon }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('icon')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description
                        </label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"
                                  placeholder="Enter category description">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('maintenance.index') }}" 
                       class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fa-solid fa-plus mr-2"></i>
                        Create Category
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
