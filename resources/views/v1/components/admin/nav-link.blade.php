@props(['icon', 'label', 'route' => null, 'subItems' => [], 'isActive' => false])

@php
    $hasSubItems = !empty($subItems);
    $isActive = $isActive || ($route && request()->routeIs($route));
@endphp

<div class="space-y-0.5">
    @if($hasSubItems)
        {{-- Parent nav item with dropdown --}}
        <button class="nav-item w-full flex items-center justify-between py-2.5 px-3 text-gray-300 hover:bg-dark-800 hover:text-white rounded-lg group transition-all duration-200 font-medium"
                onclick="toggleSubnav('{{ $label }}')"
                aria-expanded="false"
                aria-controls="subnav-{{ $label }}"
                data-tooltip="{{ $label }}">
            <div class="flex items-center">
                <div class="icon-container w-4 h-4 flex items-center justify-center mr-3">
                    <i class="fa-solid {{ $icon }} text-sm group-hover:text-accent-primary transition-colors duration-200"></i>
                </div>
                <span class="nav-text">{{ $label }}</span>
            </div>
            <div class="w-4 h-4 flex items-center justify-center nav-text chevron-container">
                <i class="fa-solid fa-chevron-right text-xs text-gray-400 group-hover:text-accent-primary transition-all duration-200" id="chevron-{{ $label }}"></i>
            </div>
        </button>
        
        {{-- Subnavigation items --}}
        <div class="hidden space-y-0.5 ml-4 subnav-items" id="subnav-{{ $label }}">
            @foreach($subItems as $subItem)
                <a href="{{ route($subItem['route']) }}" 
                   class="nav-item flex items-center py-2 px-3 text-sm text-gray-400 hover:text-white hover:bg-dark-800 rounded-lg transition-all duration-200 group"
                   data-tooltip="{{ $subItem['label'] }}">
                    <div class="w-0.5 h-4 bg-gray-500 group-hover:bg-accent-primary transition-colors duration-200 mr-3"></div>
                    <span class="font-medium nav-text">{{ $subItem['label'] }}</span>
                    @if(request()->routeIs($subItem['route']))
                        <div class="ml-auto w-1.5 h-1.5 bg-accent-primary rounded-full"></div>
                    @endif
                </a>
            @endforeach
        </div>
    @else
        {{-- Regular nav item --}}
        <a href="{{ $route ? route($route) : '#' }}" 
           class="nav-item flex items-center py-2.5 px-3 text-gray-300 hover:bg-dark-800 hover:text-white rounded-lg group transition-all duration-200 font-medium {{ $isActive ? 'bg-dark-800 text-white border-l-2 border-accent-primary' : '' }}"
           data-tooltip="{{ $label }}">
            <div class="icon-container w-4 h-4 flex items-center justify-center mr-3">
                <i class="fa-solid {{ $icon }} text-sm group-hover:text-accent-primary transition-colors duration-200 {{ $isActive ? 'text-accent-primary' : '' }}"></i>
            </div>
            <span class="nav-text">{{ $label }}</span>
            @if($isActive)
                <div class="ml-auto w-1.5 h-1.5 bg-accent-primary rounded-full"></div>
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
            if (document.getElementById('sidebar').classList.contains('sidebar-collapsed')) {
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
