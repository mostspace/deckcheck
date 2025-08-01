@props(['icon', 'label'])

<a href="#" class="flex items-center py-2 px-3 text-gray-300 hover:bg-dark-600 rounded-md group">
    <i class="fa-solid {{ $icon }} w-5 group-hover:text-accent-primary"></i>
    <span class="ml-3">{{ $label }}</span>
</a>
