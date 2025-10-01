@props(['icon', 'label', 'route' => null, 'subItems' => [], 'isActive' => false])

@php
    $hasSubItems = !empty($subItems);
    $isActive = $isActive || ($route && request()->routeIs($route));
@endphp

<div class="space-y-0.5">
    @if ($hasSubItems)
        {{-- Parent nav item with dropdown --}}
        <button
            class="nav-item hover:bg-dark-800 group flex w-full items-center justify-between rounded-lg px-3 py-2.5 font-medium text-gray-300 transition-all duration-200 hover:text-white"
            onclick="toggleSubnav('{{ $label }}')" aria-expanded="false" aria-controls="subnav-{{ $label }}"
            data-tooltip="{{ $label }}">
            <div class="flex items-center">
                <div class="icon-container mr-3 flex h-4 w-4 items-center justify-center">
                    <i
                        class="fa-solid {{ $icon }} group-hover:text-accent-primary text-sm transition-colors duration-200"></i>
                </div>
                <span class="nav-text">{{ $label }}</span>
            </div>
            <div class="nav-text chevron-container flex h-4 w-4 items-center justify-center">
                <i class="fa-solid fa-chevron-right group-hover:text-accent-primary text-xs text-gray-400 transition-all duration-200"
                    id="chevron-{{ $label }}"></i>
            </div>
        </button>

        {{-- Subnavigation items --}}
        <div class="subnav-items ml-4 hidden space-y-0.5" id="subnav-{{ $label }}">
            @foreach ($subItems as $subItem)
                <a href="{{ route($subItem['route']) }}"
                    class="nav-item hover:bg-dark-800 group flex items-center rounded-lg px-3 py-2 text-sm text-gray-400 transition-all duration-200 hover:text-white"
                    data-tooltip="{{ $subItem['label'] }}">
                    <div
                        class="group-hover:bg-accent-primary mr-3 h-4 w-0.5 bg-gray-500 transition-colors duration-200">
                    </div>
                    <span class="nav-text font-medium">{{ $subItem['label'] }}</span>
                    @if (request()->routeIs($subItem['route']))
                        <div class="bg-accent-primary ml-auto h-1.5 w-1.5 rounded-full"></div>
                    @endif
                </a>
            @endforeach
        </div>
    @else
        {{-- Regular nav item --}}
        <a href="{{ $route ? route($route) : '#' }}"
            class="nav-item hover:bg-dark-800 {{ $isActive ? 'bg-dark-800 text-white border-l-2 border-accent-primary' : '' }} group flex items-center rounded-lg px-3 py-2.5 font-medium text-gray-300 transition-all duration-200 hover:text-white"
            data-tooltip="{{ $label }}">
            <div class="icon-container mr-3 flex h-4 w-4 items-center justify-center">
                <i
                    class="fa-solid {{ $icon }} group-hover:text-accent-primary {{ $isActive ? 'text-accent-primary' : '' }} text-sm transition-colors duration-200"></i>
            </div>
            <span class="nav-text">{{ $label }}</span>
            @if ($isActive)
                <div class="bg-accent-primary ml-auto h-1.5 w-1.5 rounded-full"></div>
            @endif
        </a>
    @endif
</div>

<script>
    function toggleSubnav(label) {
        const subnav = document.getElementById(`subnav-${label}`);
        const chevron = document.getElementById(`chevron-${label}`);
        const button = chevron.parentElement.parentElement;

        if (subnav.classList.contains('hidden')) {
            subnav.classList.remove('hidden');
            chevron.style.transform = 'rotate(90deg)';
            button.setAttribute('aria-expanded', 'true');
        } else {
            subnav.classList.add('hidden');
            chevron.style.transform = 'rotate(0deg)';
            button.setAttribute('aria-expanded', 'false');
        }
    }

    // Auto-expand subnav if any sub-item is active
    document.addEventListener('DOMContentLoaded', function() {
        const activeSubItems = document.querySelectorAll('[data-active="true"]');
        activeSubItems.forEach(item => {
            const subnav = item.closest('[id^="subnav-"]');
            if (subnav) {
                const label = subnav.id.replace('subnav-', '');
                const chevron = document.getElementById(`chevron-${label}`);
                const button = chevron.parentElement.parentElement;

                subnav.classList.remove('hidden');
                chevron.style.transform = 'rotate(90deg)';
                button.setAttribute('aria-expanded', 'true');
            }
        });

        // Add tooltip functionality
        const tooltipElements = document.querySelectorAll('[data-tooltip]');
        tooltipElements.forEach(element => {
            element.addEventListener('mouseenter', function() {
                // Only show tooltips when sidebar is collapsed
                if (document.getElementById('sidebar').classList.contains(
                        'sidebar-collapsed')) {
                    const tooltip = document.createElement('div');
                    tooltip.className = 'tooltip-text';
                    tooltip.textContent = this.getAttribute('data-tooltip');
                    tooltip.style.position = 'absolute';
                    tooltip.style.zIndex = '1000';
                    tooltip.style.left = '100%';
                    tooltip.style.top = '50%';
                    tooltip.style.transform = 'translateY(-50%)';
                    tooltip.style.marginLeft = '8px';
                    tooltip.style.backgroundColor = '#1E293B';
                    tooltip.style.color = '#fff';
                    tooltip.style.padding = '8px 12px';
                    tooltip.style.borderRadius = '6px';
                    tooltip.style.fontSize = '12px';
                    tooltip.style.fontWeight = '500';
                    tooltip.style.whiteSpace = 'nowrap';
                    tooltip.style.border = '1px solid rgba(148, 163, 184, 0.2)';

                    this.appendChild(tooltip);
                }
            });

            element.addEventListener('mouseleave', function() {
                const tooltip = this.querySelector('.tooltip-text');
                if (tooltip) {
                    tooltip.remove();
                }
            });
        });
    });
</script>
