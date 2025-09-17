@props([
    'breadcrumbs' => [],
    'actions' => []
])

<!-- Sub header -->
<section class="sticky top-16 z-40 px-3 sm:px-5 lg:px-8 py-3 border-b bg-white flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-2 flex-shrink-0">
    <!-- Breadcrumbs -->
    <div class="flex items-center rounded-md bg-white" id="breadcrumb-container">
        @if(count($breadcrumbs) > 0)
            @foreach($breadcrumbs as $index => $crumb)
                @if($index === 0)
                    <span class="inline-flex items-center gap-1 sm:gap-2 text-xs px-2 sm:px-3 py-1.5 rounded-md border border-primary-500 bg-accent-200/40 text-slate-900 z-10 shadow-soft">
                        @if(isset($crumb['icon']))
                            <img src="{{ $crumb['icon'] }}" alt="{{ $crumb['label'] }}" class="w-3 h-3" />
                        @endif
                        <span>{{ $crumb['label'] }}</span>
                    </span>
                @else
                    <span class="inline-flex items-center text-xs px-2 sm:px-3 pl-6 sm:pl-7 py-1.5 -ml-4 sm:-ml-5 text-slate-500 rounded-md border border-slate-200">
                        {{ $crumb['label'] }}
                    </span>
                @endif
            @endforeach
        @else
            <!-- Dynamic breadcrumb based on page and active tab -->
            <span class="inline-flex items-center gap-1 sm:gap-2 text-xs px-2 sm:px-3 py-1.5 rounded-md border border-primary-500 bg-accent-200/40 text-slate-900 z-10 shadow-soft" id="page-breadcrumb">
                <img src="{{ $pageIcon ?? asset('assets/media/icons/sidebar-solid-wrench-scredriver.svg') }}" alt="{{ $pageName ?? 'Page' }}" class="w-3 h-3" />
                <span>{{ $pageName ?? 'Page' }}</span>
            </span>
            <span class="inline-flex items-center text-xs px-2 sm:px-3 pl-6 sm:pl-7 py-1.5 -ml-4 sm:-ml-5 text-slate-500 rounded-md border border-slate-200" id="current-tab-breadcrumb">{{ $activeTab ?? 'Tab' }}</span>
        @endif
    </div>

    <!-- Action Buttons -->
    <div class="hidden sm:flex items-center gap-2 lg:gap-4">
        @if(count($actions) > 0)
            @foreach($actions as $action)
                <button class="icon-hover-btn" title="{{ $action['title'] }}" aria-label="{{ $action['aria_label'] ?? $action['title'] }}">
                    <img src="{{ $action['icon'] }}" class="w-6 h-6 icon-normal" alt="{{ $action['title'] }}" />
                    @if(isset($action['icon_solid']))
                        <img src="{{ $action['icon_solid'] }}" class="w-6 h-6 icon-solid hidden" alt="{{ $action['title'] }}" />
                    @endif
                </button>
            @endforeach
        @else
            <!-- Default action buttons -->
            <button class="icon-hover-btn" title="Share" aria-label="Share">
                <img src="{{ asset('assets/media/icons/plus-circle.svg') }}" class="w-6 h-6 icon-normal" alt="Share" />
                <img src="{{ asset('assets/media/icons/plus-circle-solid.svg') }}" class="w-6 h-6 icon-solid hidden" alt="Share" />
            </button>
            <button class="icon-hover-btn" title="Settings" aria-label="Settings">
                <img src="{{ asset('assets/media/icons/chat-bubble.svg') }}" class="w-6 h-6 icon-normal" alt="Settings" />
                <img src="{{ asset('assets/media/icons/chat-bubble-solid.svg') }}" class="w-6 h-6 icon-solid hidden" alt="Settings" />
            </button>
            <button class="icon-hover-btn" title="Help" aria-label="Help">
                <img src="{{ asset('assets/media/icons/question-mark-circle.svg') }}" class="w-6 h-6 icon-normal" alt="Help" />
                <img src="{{ asset('assets/media/icons/question-mark-circle-solid.svg') }}" class="w-6 h-6 icon-solid hidden" alt="Help" />
            </button>
        @endif
    </div>
</section>
