@extends('v2.layouts.app')

@section('title', 'Create Maintenance Category')

@section('content')

    @include('v2.components.nav.header-routing', [
        'activeTab' => 'index',
        'breadcrumbs' => [
            ['label' => 'Maintenance', 'icon' => asset('assets/media/icons/sidebar-solid-wrench-scredriver.svg')],
            ['label' => 'Index', 'url' => route('maintenance.index')],
            ['label' => 'Create Category']
        ]
    ])

    <div class="flex flex-col gap-4 px-3 py-4 sm:gap-8 sm:px-6 sm:py-6 lg:px-8">
        {{-- Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-[#0f1728]">Create Maintenance Category</h1>
                <p class="text-[#475466]">Add a new maintenance category to organize equipment and intervals.</p>
            </div>
        </div>

        {{-- Form --}}
        <div class="rounded-lg border bg-white shadow">
            <form method="POST" action="{{ route('maintenance.store') }}" class="p-6">
                @csrf

                <div class="grid grid-cols-1 gap-6">
                    {{-- Category Name --}}
                    <div>
                        <label for="name" class="mb-2 block text-sm font-medium text-gray-700">
                            Category Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                            class="@error('name') border-red-500 @enderror w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-transparent focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter category name" required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Category Type --}}
                    <div>
                        <label for="type" class="mb-2 block text-sm font-medium text-gray-700">
                            Category Type <span class="text-red-500">*</span>
                        </label>
                        <select id="type" name="type"
                            class="@error('type') border-red-500 @enderror w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-transparent focus:ring-2 focus:ring-blue-500"
                            required>
                            <option value="">Select a type</option>
                            @foreach ($types as $type)
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
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Category Icon <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-6 gap-3">
                            @foreach ($icons as $icon)
                                <label
                                    class="{{ old('icon') == $icon ? 'border-blue-500 bg-blue-50' : 'border-gray-300' }} flex cursor-pointer flex-col items-center rounded-lg border p-3 hover:bg-gray-50">
                                    <input type="radio" name="icon" value="{{ $icon }}" class="sr-only"
                                        {{ old('icon') == $icon ? 'checked' : '' }}>
                                    <i class="fa-solid {{ $icon }} mb-2 text-2xl text-gray-600"></i>
                                    <span class="text-center text-xs text-gray-600">{{ $icon }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('icon')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="description" class="mb-2 block text-sm font-medium text-gray-700">
                            Description
                        </label>
                        <textarea id="description" name="description" rows="3"
                            class="@error('description') border-red-500 @enderror w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-transparent focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter category description">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="mt-8 flex justify-end space-x-4 border-t border-gray-200 pt-6">
                    <a href="{{ route('maintenance.index') }}"
                        class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-gray-700 transition-colors hover:bg-gray-50">
                        Cancel
                    </a>
                    <x-v2.components.ui.button type="submit" icon="fa-solid fa-plus">
                        Create Category
                    </x-v2.components.ui.button>
                </div>
            </form>
        </div>
    </div>
@endsection
