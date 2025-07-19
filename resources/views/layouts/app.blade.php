<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'DeckCheck')</title>

    {{-- Tailwind CSS & FontAwesome --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">



    {{-- Fonts --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;500;600;700;800;900&display=swap">

    <style>
        * {
            font-family: "Inter", sans-serif;
        }

        ::-webkit-scrollbar {
            display: none;
        }

        html,
        body {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        body { background: #f8f9fb; }
        .transition-width { transition: width 0.3s cubic-bezier(.4,0,.2,1); }
        .transition-margin { transition: margin-left 0.3s cubic-bezier(.4,0,.2,1); }
    </style>


    @stack('styles')
</head>

<body class="h-full text-base-content">

    @php
        $user = auth()->user();
    @endphp

    <div id="app-layout" class="flex">
    @include('components.main.sidebar-test')

    <div class="flex-1 flex flex-col min-h-screen">
        <main id="main-content" class="flex-1 ml-14 p-8 transition-margin duration-300 ease-in-out">
            @include('components.main.notification-button')
            @include('components.main.user-modal')
            @yield('content')
        </main>
    </div>
</div>


    @push('scripts')
        
        <script>
            // Toggle user modal
            document.getElementById('sidebar-user-btn').addEventListener('click', function() {
                document.getElementById('user-modal').classList.remove('hidden');
            });

            // Close modal
            document.getElementById('close-modal').addEventListener('click', function() {
                document.getElementById('user-modal').classList.add('hidden');
            });

            // Close modal when clicking outside
            document.getElementById('user-modal').addEventListener('click', function(event) {
                if (event.target === this) {
                    this.classList.add('hidden');
                }
            });
        </script>

        
    @endpush
    @stack('scripts')
</body>

</html>
