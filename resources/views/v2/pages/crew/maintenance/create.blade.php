@extends('v2.layouts.app')

@section('title', 'Maintenance New Category')

@php
    $title = 'New Category';
    $subtitle = 'Define new maintenance index category';
@endphp

@section('content')

    @include('v2.components.nav.header', [
        'tabs' => [],
        'pageName' => 'Maintenance',
        'pageIcon' => asset('assets/media/icons/sidebar-solid-wrench-scredriver.svg'),
        'activeTab' => 'Index',
        'breadcrumbs' => [
            ['label' => 'Maintenance', 'url' => route('maintenance.index')],
            ['label' => 'Index', 'url' => route('maintenance.index')],
            ['label' => 'New Category', 'url' => null]
        ],
        'showTopHeader' => false
    ])

    <div class="flex flex-col gap-4 sm:gap-8 px-3 sm:px-6 lg:px-8 py-4 sm:py-6">
        <div class="">
            <h1 class="text-2xl font-semibold text-[#0f1728]">{{ $title ?? 'New Record' }}</h1>
            @isset($subtitle)
                <p class="text-[#475466]">{!! $subtitle !!}</p>
            @endisset
        </div>

        <div>
            <section class="bg-white rounded-lg border border-[#e4e7ec] p-6">
                
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-100 border border-red-300 text-red-700 rounded-lg">
                        <ul class="list-disc pl-5 space-y-1 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('maintenance.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    {{-- Hidden Vessel ID --}}
                    <input type="hidden" name="vessel_id" value="{{ $vessel->id }}">

                    {{-- Location Name --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-[#344053] mb-2">Name</label>
                        <input name="name" id="name" value="{{ old('name') }}" type="text"
                            class="w-full px-3.5 py-2.5 bg-white rounded-lg border border-[#cfd4dc] text-[#0f1728] text-base focus:border-[#6840c6] focus:outline-none placeholder-[#667084]"
                            placeholder="Enter category name">
                    </div>

                    {{-- Type --}}
                    <div>
                        <label for="type" class="block text-sm font-medium text-[#344053] mb-2">Type</label>
                        <select name="type" id="type"
                            class="appearance-none w-full px-3.5 pr-10 py-2.5 bg-white rounded-lg border border-[#cfd4dc] text-[#344053] text-sm focus:outline-none focus:ring-2 focus:ring-[#6840c6] focus:border-transparent">
                            <option value="">Select type...</option>

                            @foreach ($types as $type)
                                <option value="{{ $type }}" {{ old('type') === $type ? 'selected' : '' }}>
                                    {{ ucfirst($type) }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    {{-- Icon Picker --}}
                    <div>
                        <label class="block text-sm font-medium text-[#344053] mb-2">Select Icon</label>

                        <input type="hidden" name="icon" id="icon-input" value="{{ old('icon') }}">

                        <div id="icon-picker" class="w-1/3 grid grid-cols-8 gap-4">
                            @foreach ($icons as $icon)
                                <div 
                                    class="relative w-8 h-8 bg-white rounded-md flex items-center justify-center cursor-pointer border transition-all
                                            {{ old('icon') === $icon ? 'bg-indigo-50' : 'border-[#fff]' }}"
                                    data-icon="{{ $icon }}"
                                    onclick="selectIcon(this)"
                                >
                                    {{-- Actual icon --}}
                                    <i class="fa-solid {{ $icon }} text-[16px] {{ old('icon') === $icon ? 'text-indigo-600' : 'text-[#6840c6]' }}"></i>

                                    {{-- Checkmark overlay --}}
                                    @if (old('icon') === $icon)
                                        <div class="absolute -top-1.5 -right-1.5  text-white rounded-full text-[10px]">
                                            <i class="fa-solid fa-circle-check"></i>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        @error('icon')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Buttons --}}
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('maintenance.index') }}"
                            class="px-4 py-2.5 bg-white text-[#344053] border border-[#cfd4dc] rounded-lg text-sm font-medium hover:bg-[#f9fafb] transition-colors">
                            Cancel
                        </a>

                        <button type="submit"
                            class="px-4 py-2.5 bg-primary-500 text-slate-800 rounded-lg text-sm font-medium hover:bg-primary-600 transition-colors">
                            <i class="fa-solid fa-plus mr-2"></i>
                            Save Category
                        </button>
                    </div>
                </form>
            </section>
        </div>
    </div>

    {{-- Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            window.selectIcon = function (element) {
                // Deselect all
                document.querySelectorAll('#icon-picker div[data-icon]').forEach(div => {
                    div.classList.remove('bg-violet-50', 'border-indigo-500');
                    div.classList.add('bg-white', 'border-[#e4e7ec]');

                    const icon = div.querySelector('i');
                    if (icon) {
                        icon.classList.replace('text-indigo-600', 'text-[#6840c6]');
                    }

                    // Remove checkmark
                    const check = div.querySelector('.checkmark');
                    if (check) check.remove();
                });

                // Select this one
                const iconValue = element.getAttribute('data-icon');
                element.classList.add('bg-violet-50');
                element.classList.remove('bg-white', 'border-[#e4e7ec]');

                const selectedIcon = element.querySelector('i');
                if (selectedIcon) {
                    selectedIcon.classList.replace('text-[#6840c6]', 'text-indigo-600');
                }

                // Add checkmark
                const checkmark = document.createElement('div');
                checkmark.className = 'checkmark absolute -top-1.5 -right-1.5 text-green-600 rounded-full bg-white text-[12px]';
                checkmark.innerHTML = '<i class="fa-solid fa-circle-check"></i>';
                element.appendChild(checkmark);

                document.getElementById('icon-input').value = iconValue;
            };
        });
    </script>
@endsection