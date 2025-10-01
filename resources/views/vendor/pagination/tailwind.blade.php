@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">
        <div class="flex flex-1 justify-between sm:hidden">
            @if ($paginator->onFirstPage())
                <span
                    class="bg-dark-700 border-dark-600 relative inline-flex cursor-default items-center rounded-md border px-4 py-2 text-sm font-medium text-gray-500">
                    {!! __('pagination.previous') !!}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                    class="bg-dark-700 border-dark-600 hover:bg-dark-600 relative inline-flex items-center rounded-md border px-4 py-2 text-sm font-medium text-gray-300 transition-colors duration-200 hover:text-white">
                    {!! __('pagination.previous') !!}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                    class="bg-dark-700 border-dark-600 hover:bg-dark-600 relative ml-3 inline-flex items-center rounded-md border px-4 py-2 text-sm font-medium text-gray-300 transition-colors duration-200 hover:text-white">
                    {!! __('pagination.next') !!}
                </a>
            @else
                <span
                    class="bg-dark-700 border-dark-600 relative ml-3 inline-flex cursor-default items-center rounded-md border px-4 py-2 text-sm font-medium text-gray-500">
                    {!! __('pagination.next') !!}
                </span>
            @endif
        </div>

        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-400">
                    {!! __('Showing') !!}
                    <span class="font-medium text-white">{{ $paginator->firstItem() }}</span>
                    {!! __('to') !!}
                    <span class="font-medium text-white">{{ $paginator->lastItem() }}</span>
                    {!! __('of') !!}
                    <span class="font-medium text-white">{{ $paginator->total() }}</span>
                    {!! __('results') !!}
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex rounded-md shadow-sm">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span
                                class="bg-dark-700 border-dark-600 relative inline-flex cursor-default items-center rounded-l-md border px-2 py-2 text-sm font-medium text-gray-500"
                                aria-hidden="true">
                                <i class="fa-solid fa-chevron-left"></i>
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                            class="bg-dark-700 border-dark-600 hover:bg-dark-600 relative inline-flex items-center rounded-l-md border px-2 py-2 text-sm font-medium text-gray-300 transition-colors duration-200 hover:text-white"
                            aria-label="{{ __('pagination.previous') }}">
                            <i class="fa-solid fa-chevron-left"></i>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span
                                    class="bg-dark-700 border-dark-600 relative -ml-px inline-flex cursor-default items-center border px-4 py-2 text-sm font-medium text-gray-500">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span
                                            class="bg-accent-primary border-accent-primary relative -ml-px inline-flex cursor-default items-center border px-4 py-2 text-sm font-medium text-white">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}"
                                        class="bg-dark-700 border-dark-600 hover:bg-dark-600 relative -ml-px inline-flex items-center border px-4 py-2 text-sm font-medium text-gray-300 transition-colors duration-200 hover:text-white"
                                        aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                            class="bg-dark-700 border-dark-600 hover:bg-dark-600 relative -ml-px inline-flex items-center rounded-r-md border px-2 py-2 text-sm font-medium text-gray-300 transition-colors duration-200 hover:text-white"
                            aria-label="{{ __('pagination.next') }}">
                            <i class="fa-solid fa-chevron-right"></i>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                            <span
                                class="bg-dark-700 border-dark-600 relative -ml-px inline-flex cursor-default items-center rounded-r-md border px-2 py-2 text-sm font-medium text-gray-500"
                                aria-hidden="true">
                                <i class="fa-solid fa-chevron-right"></i>
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
