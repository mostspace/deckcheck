@props(['icon', 'label', 'route' => null, 'subItems' => [], 'isActive' => false])

@php
    $hasSubItems = !empty($subItems);
    $isActive = $isActive || ($route && request()->routeIs($route));
@endphp

<div class="space-y-1">
    @if($hasSubItems)
        {{-- Parent nav item with dropdown --}}
        <button class="w-full flex items-center justify-between py-2 px-3 text-gray-300 hover:bg-dark-600 rounded-md group transition-colors duration-200"
                onclick="toggleSubnav('{{ $label }}')"
                aria-expanded="false"
                aria-controls="subnav-{{ $label }}">
            <div class="flex items-center">
                <i class="fa-solid {{ $icon }} w-5 group-hover:text-accent-primary transition-colors duration-200"></i>
                <span class="ml-3">{{ $label }}</span>
            </div>
            <i class="fa-solid fa-chevron-right text-xs text-gray-400 transition-transform duration-200" id="chevron-{{ $label }}"></i>
        </button>
        
        {{-- Subnavigation items --}}
        <div class="hidden space-y-1 ml-6" id="subnav-{{ $label }}">
            @foreach($subItems as $subItem)
                <a href="{{ route($subItem['route']) }}" 
                   class="flex items-center py-2 px-3 text-sm text-gray-400 hover:text-white hover:bg-dark-600 rounded-md transition-colors duration-200 group">
                    <div class="w-2 h-2 rounded-full bg-gray-500 group-hover:bg-accent-primary transition-colors duration-200 mr-3"></div>
                    <span>{{ $subItem['label'] }}</span>
                    @if(request()->routeIs($subItem['route']))
                        <i class="fa-solid fa-arrow-right text-accent-primary ml-auto text-xs"></i>
                    @endif
                </a>
            @endforeach
        </div>
    @else
        {{-- Regular nav item --}}
        <a href="{{ $route ? route($route) : '#' }}" 
           class="flex items-center py-2 px-3 text-gray-300 hover:bg-dark-600 rounded-md group transition-colors duration-200 {{ $isActive ? 'bg-dark-600 text-white' : '' }}">
            <i class="fa-solid {{ $icon }} w-5 group-hover:text-accent-primary transition-colors duration-200 {{ $isActive ? 'text-accent-primary' : '' }}"></i>
            <span class="ml-3">{{ $label }}</span>
            @if($isActive)
                <i class="fa-solid fa-arrow-right text-accent-primary ml-auto text-xs"></i>
            @endif
        </a>
    @endif
</div>

<script>
function toggleSubnav(label) {
    const subnav = document.getElementById(`subnav-${label}`);
    const chevron = document.getElementById(`chevron-${label}`);
    const button = chevron.parentElement;
    
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
            const button = chevron.parentElement;
            
            subnav.classList.remove('hidden');
            chevron.style.transform = 'rotate(90deg)';
            button.setAttribute('aria-expanded', 'true');
        }
    });
});
</script>
