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
    
    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/css/sidebar.css', 'resources/js/app.js', 'resources/js/sidebar.js'])

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

<body class="h-dvh bg-white text-base-content">

    @php
      $user = auth()->user();
    @endphp

    <!-- <div id="app-layout" class="flex"> -->
    <div id="app-layout" class="w-full h-full grid md:grid-cols-[56px_1fr]">
        @include('v2.components.navigation.sidebar')

        <main class="relative flex flex-col h-full overflow-hidden">
            @include('v2.components.navigation.page-header')
            @include('v2.components.navigation.sub-header')

            <!-- <section class="flex-1 flex flex-col min-h-screen"> -->
            <section class="relative flex-1 min-h-0">
                <!-- <div id="main-content" class="flex-1 md:ml-20 p-8 transition-margin duration-300 ease-in-out"> -->
                <div id="main-content" class="h-full rounded-md px-3 sm:px-6 lg:px-8 py-4 sm:py-6 bg-white overflow-y-auto">
                    <!-- @include('components.main.notification-button') -->
                    @include('components.main.user-modal')
                    @yield('content')

                    <!-- Right Drawer (responsive) -->
                    <aside id="infoDrawer" class="absolute top-0 right-0 w-full sm:max-w-sm h-full border-l hidden translate-x-full transition-transform duration-200 ease-out bg-[#F8F8F6] z-30 overflow-hidden">
                        <div class="h-full overflow-y-auto">
                            <div class="flex items-start justify-between p-4 sm:p-6 border-b border-slate-200">
                                <div class="flex items-center gap-2">
                                    <img src="./assets/media/icons/tab-index-solid.svg" class="h-4 w-4 text-slate-800" />
                                    <h2 class="text-sm sm:text-base text-slate-800 font-semibold">Index page</h2>
                                </div>
                                <button id="btnCloseDrawer" class="p-1 rounded hover:bg-slate-200/60" aria-label="Close details">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 6l12 12M18 6 6 18"/></svg>
                                </button>
                            </div>
                            
                            <div class="space-y-4 sm:space-y-6 p-4 sm:p-6">
                                <!-- Overview Section -->
                                <div>
                                    <div class="flex items-center gap-2 mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M3.28125 0.875C2.67719 0.875 2.1875 1.36469 2.1875 1.96875V12.0312C2.1875 12.6353 2.67719 13.125 3.28125 13.125H10.7188C11.3228 13.125 11.8125 12.6353 11.8125 12.0312V7.4375C11.8125 6.22938 10.8331 5.25 9.625 5.25H8.53125C7.92719 5.25 7.4375 4.76031 7.4375 4.15625V3.0625C7.4375 1.85438 6.45812 0.875 5.25 0.875H3.28125ZM4.375 8.75C4.375 8.50838 4.57088 8.3125 4.8125 8.3125H9.1875C9.42912 8.3125 9.625 8.50838 9.625 8.75C9.625 8.99162 9.42912 9.1875 9.1875 9.1875H4.8125C4.57088 9.1875 4.375 8.99162 4.375 8.75ZM4.8125 10.0625C4.57088 10.0625 4.375 10.2584 4.375 10.5C4.375 10.7416 4.57088 10.9375 4.8125 10.9375H7C7.24162 10.9375 7.4375 10.7416 7.4375 10.5C7.4375 10.2584 7.24162 10.0625 7 10.0625H4.8125Z" fill="#0F172A"/>
                                            <path d="M7.56653 1.05928C8.03131 1.59628 8.3125 2.29655 8.3125 3.0625V4.15625C8.3125 4.27706 8.41044 4.375 8.53125 4.375H9.625C10.3909 4.375 11.0912 4.65619 11.6282 5.12097C11.1103 3.13826 9.54924 1.5772 7.56653 1.05928Z" fill="#0F172A"/>
                                        </svg>
                                        <h3 class="text-sm sm:text-base text-slate-800 font-semibold">Overview:</h3>
                                    </div>
                                    <ul class="space-y-2 text-xs sm:text-sm text-slate-600">
                                        <li class="flex items-start gap-2">
                                            <div class="w-1 h-1 bg-accent-500 rounded-full mt-2 flex-shrink-0"></div>
                                            <span>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</span>
                                        </li>
                                        <li class="flex items-start gap-2">
                                            <div class="w-1 h-1 bg-accent-500 rounded-full mt-2 flex-shrink-0"></div>
                                            <span>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</span>
                                        </li>
                                        <li class="flex items-start gap-2">
                                            <div class="w-1 h-1 bg-accent-500 rounded-full mt-2 flex-shrink-0"></div>
                                            <span>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</span>
                                        </li>
                                    </ul>
                                </div>
                                
                                <div class="border-b border-slate-200"></div>
                                
                                <!-- Details Section -->
                                <div>
                                    <div class="flex items-center gap-2 mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 12 12" fill="none">
                                            <path d="M6 0.400391C9.09279 0.400391 11.5996 2.90721 11.5996 6C11.5996 9.09279 9.09279 11.5996 6 11.5996C2.90721 11.5996 0.400391 9.09279 0.400391 6C0.400391 2.90721 2.90721 0.400391 6 0.400391ZM5.2998 5.2998C5.00995 5.29991 4.77539 5.53531 4.77539 5.8252C4.7755 6.11499 5.01001 6.3495 5.2998 6.34961H5.47754C5.58924 6.34971 5.67235 6.45345 5.64844 6.5625L5.32715 8.00879C5.15715 8.77376 5.73886 9.49989 6.52246 9.5H6.7002C6.99006 9.4999 7.22461 9.26449 7.22461 8.97461C7.2244 8.6849 6.98992 8.4503 6.7002 8.4502H6.52246C6.41069 8.45009 6.32751 8.34643 6.35156 8.2373L6.67285 6.79102C6.84285 6.02604 6.26114 5.29991 5.47754 5.2998H5.2998ZM6 2.5C5.6134 2.5 5.2998 2.8136 5.2998 3.2002C5.29991 3.5867 5.61347 3.90039 6 3.90039C6.38653 3.90039 6.70009 3.5867 6.7002 3.2002C6.7002 2.8136 6.3866 2.5 6 2.5Z" fill="#0F172A"/>
                                        </svg>
                                        <h3 class="text-sm sm:text-base text-slate-800 font-semibold">Details:</h3>
                                    </div>
                                    <p class="text-xs sm:text-sm text-slate-600 leading-5 sm:leading-6">
                                    Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat.                </p>
                                </div>
                            </div>
                        </div>
                    </aside>
                </div>
            </section>
        </main>

        <!-- New Page Header -->
    </div>

    <!-- Easter Egg Modal (Full Screen) -->
    <div id="ee-modal" class="fixed inset-0 z-[9999] hidden">
        <div class="absolute inset-0 bg-black/90"></div>
        <canvas id="ee-canvas" class="absolute inset-0 w-full h-full"></canvas>
        <div class="absolute top-0 left-0 right-0 p-3 flex items-center justify-between text-white text-xs">
        <div class="opacity-80">Press <kbd class="px-1 py-0.5 bg-white/20 rounded">Esc</kbd> or <kbd class="px-1 py-0.5 bg-white/20 rounded">Space</kbd> to exit</div>
        <div class="opacity-80">Controls: ← → move • ↓ soft drop • ↑ rotate</div>
        </div>
    </div>
  
    <script>
        (function() {
            // ---- Config ----
            const ENABLED = true;               // flip to false to disable entirely
            const REQUIRE_ROLE = null;          // e.g. 'admin' (wire from backend if desired)
            const CHORD = { ctrl: true, alt: true, shift: true, key: 'g' }; // Ctrl+Alt+Shift+G
            const USE_SEQUENCE = true;          // Optional: Konami-like sequence
            const SEQUENCE = ['ArrowUp','ArrowUp','ArrowDown','ArrowDown','ArrowLeft','ArrowRight','ArrowLeft','ArrowRight','b','a'];
            const isMac = /Mac|iPhone|iPad/.test(navigator.platform);
            const chordKeyCode = (typeof CHORD.key === 'string' && CHORD.key.length === 1)
            ? ('Key' + CHORD.key.toUpperCase())
            : CHORD.key;
        
            // ---- Guards ----
            if (!ENABLED) return;
            // Example: require role (inject role server-side as a data- attribute or global var)
            // if (REQUIRE_ROLE && window.__userRole !== REQUIRE_ROLE) return;
        
            // Don't trigger inside inputs/textareas/contenteditable
            const isTypingContext = (el) => {
            if (!el) return false;
            const tag = el.tagName?.toLowerCase();
            return tag === 'input' || tag === 'textarea' || el.isContentEditable || tag === 'select';
            };
        
            // ---- Modal controls ----
            const modal = document.getElementById('ee-modal');
            const openModal = () => {
            if (!modal) return;
            modal.classList.remove('hidden');
            document.addEventListener('keydown', handleKeyClose);
            // Lazy start the mini-game
            startRegattaTetris();
            };
            const closeModal = () => {
            if (!modal) return;
            modal.classList.add('hidden');
            document.removeEventListener('keydown', handleKeyClose);
            stopRegattaTetris();
            };
            const handleKeyClose = (e) => { if (e.key === 'Escape' || e.code === 'Space') { e.preventDefault(); closeModal(); } };
        
            // ---- Trigger: Chord detection ----
            let lastChordTime = 0;
            window.addEventListener('keydown', (e) => {
            if (isTypingContext(document.activeElement)) return;
            // prevent spamming: 600ms cooldown
            const now = performance.now();
            if (now - lastChordTime < 600) return;
        
            const keyOk = e.code === chordKeyCode;
            const modifiersOk =
                (!!CHORD.alt === e.altKey) &&
                (!!CHORD.shift === e.shiftKey) &&
                (!!CHORD.ctrl === (isMac ? e.metaKey : e.ctrlKey));
            const ok = keyOk && modifiersOk;
            if (ok) {
                e.preventDefault();
                lastChordTime = now;
                openModal();
            }
            }, { passive: false });
        
            // ---- Trigger: Sequence detection (optional) ----
            if (USE_SEQUENCE) {
            let buffer = [];
            window.addEventListener('keydown', (e) => {
                if (isTypingContext(document.activeElement)) return;
                buffer.push(e.key);
                if (buffer.length > SEQUENCE.length) buffer.shift();
                if (SEQUENCE.every((k, i) => buffer[i]?.toLowerCase() === k.toLowerCase())) {
                openModal();
                buffer = [];
                }
            });
            }
        
            // ---- Regatta Tetris (yachting-themed) ----
            let raf = null;
            let running = false;
            let canvas, ctx;
            let width = 0, height = 0;
            const COLS = 10, ROWS = 20;
            let blockSize = 24;
            let boardPixelW = 0, boardPixelH = 0;
            let originX = 0, originY = 0; // top-left of board
            let board = [];
            let active = null;
            let bag = [];
            let lastTime = 0;
            let dropCounter = 0;
            let dropInterval = 650; // ms
            let controlsBound = false;
            let resizeBound = false;

            const shapes = {
            I: [[1,1,1,1]],
            J: [[1,0,0],[1,1,1]],
            L: [[0,0,1],[1,1,1]],
            O: [[1,1],[1,1]],
            S: [[0,1,1],[1,1,0]],
            T: [[0,1,0],[1,1,1]],
            Z: [[1,1,0],[0,1,1]],
            };
            // Nautical signal flag colors per piece
            const pieceTheme = {
            I: '#00B3FF', // ocean blue
            J: '#FFD000', // yellow
            L: '#FF7A00', // orange
            O: '#FFFFFF', // white
            S: '#00C853', // green
            T: '#C400FF', // magenta
            Z: '#E53935', // red
            };

            function createMatrix(rows, cols, val = 0) {
            const m = new Array(rows);
            for (let r = 0; r < rows; r++) m[r] = new Array(cols).fill(val);
            return m;
            }
            function resetBoard() { board = createMatrix(ROWS, COLS, 0); }
            function cloneMatrix(m) { return m.map(row => row.slice()); }
            function rotateMatrix(m) {
            const N = m.length, M = m[0].length;
            const res = Array.from({length: M}, () => Array(N).fill(0));
            for (let r=0;r<N;r++) for (let c=0;c<M;c++) res[c][N-1-r] = m[r][c];
            return res;
            }
            function newBag() { return Object.keys(shapes).sort(() => Math.random() - 0.5); }
            function spawnPiece() {
            if (bag.length === 0) bag = newBag();
            const id = bag.pop();
            active = {
                id,
                shape: cloneMatrix(shapes[id]),
                color: pieceTheme[id],
                x: Math.floor(COLS/2) - Math.ceil(shapes[id][0].length/2),
                y: -2,
            };
            if (collide(board, active)) {
                // game over, reset
                resetBoard();
            }
            }
            function collide(grid, piece) {
            const {shape, x: px, y: py} = piece;
            for (let r=0;r<shape.length;r++) {
                for (let c=0;c<shape[r].length;c++) {
                if (!shape[r][c]) continue;
                const x = px + c;
                const y = py + r;
                if (y >= ROWS || x < 0 || x >= COLS) return true;
                if (y >= 0 && grid[y][x]) return true;
                }
            }
            return false;
            }
            function merge(grid, piece) {
            piece.shape.forEach((row, r) => {
                row.forEach((v, c) => {
                if (v) {
                    const y = piece.y + r; const x = piece.x + c;
                    if (y >= 0) grid[y][x] = piece.color;
                }
                });
            });
            }
            function clearLines() {
            let cleared = 0;
            for (let r = ROWS-1; r >= 0; r--) {
                if (board[r].every(v => v)) {
                board.splice(r,1);
                board.unshift(new Array(COLS).fill(0));
                cleared++;
                r++;
                }
            }
            // speed up slightly
            if (cleared > 0) dropInterval = Math.max(120, dropInterval - 20 * cleared);
            }

            function resizeCanvas() {
            width = window.innerWidth; height = window.innerHeight;
            canvas.width = width; canvas.height = height;
            const margin = Math.min(width, height) * 0.05;
            blockSize = Math.floor(Math.min(
                (width - margin*2) / COLS,
                (height - margin*2) / ROWS
            ));
            boardPixelW = blockSize * COLS;
            boardPixelH = blockSize * ROWS;
            originX = Math.floor((width - boardPixelW)/2);
            originY = Math.floor((height - boardPixelH)/2);
            }

            function drawBackground(t) {
            // gradient sea + subtle waves
            const g = ctx.createLinearGradient(0, 0, 0, height);
            g.addColorStop(0, '#012a4a');
            g.addColorStop(1, '#014f86');
            ctx.fillStyle = g;
            ctx.fillRect(0,0,width,height);
            // waves
            ctx.strokeStyle = 'rgba(255,255,255,0.08)';
            ctx.lineWidth = 2;
            const amp = 8, k = 0.012, speed = 0.0015;
            for (let i=0;i<5;i++) {
                ctx.beginPath();
                const y0 = originY + boardPixelH + 20 + i*10;
                for (let x=0; x<=width; x+=8) {
                const y = y0 + Math.sin((x * k) + (t * speed) + i) * amp;
                if (x===0) ctx.moveTo(x,y); else ctx.lineTo(x,y);
                }
                ctx.stroke();
            }
            // small sailboat icon
            const boatY = originY - 20; const boatX = originX + boardPixelW - 60;
            ctx.fillStyle = 'rgba(255,255,255,0.9)';
            ctx.beginPath();
            ctx.moveTo(boatX, boatY);
            ctx.lineTo(boatX+20, boatY+12);
            ctx.lineTo(boatX-20, boatY+12);
            ctx.closePath();
            ctx.fill();
            ctx.fillRect(boatX-12, boatY-28, 4, 28);
            ctx.beginPath();
            ctx.moveTo(boatX-8, boatY-26);
            ctx.lineTo(boatX-8, boatY-4);
            ctx.lineTo(boatX+18, boatY-15);
            ctx.closePath();
            ctx.fill();
            }

            function drawCell(px, py, color) {
            ctx.fillStyle = color;
            ctx.fillRect(px, py, blockSize, blockSize);
            ctx.strokeStyle = 'rgba(255,255,255,0.2)';
            ctx.strokeRect(px+0.5, py+0.5, blockSize-1, blockSize-1);
            }
            function drawBoard() {
            // board background panel
            ctx.fillStyle = 'rgba(0,0,0,0.25)';
            ctx.fillRect(originX-6, originY-6, boardPixelW+12, boardPixelH+12);
            for (let r=0;r<ROWS;r++) {
                for (let c=0;c<COLS;c++) {
                const v = board[r][c];
                if (v) drawCell(originX + c*blockSize, originY + r*blockSize, v);
                }
            }
            }
            function drawPiece(p) {
            p.shape.forEach((row, r) => {
                row.forEach((v, c) => {
                if (!v) return;
                const x = p.x + c; const y = p.y + r;
                if (y >= 0) drawCell(originX + x*blockSize, originY + y*blockSize, p.color);
                });
            });
            }

            function tryMove(dx, dy) {
            const next = {...active, x: active.x + dx, y: active.y + dy};
            if (!collide(board, next)) { active = next; return true; }
            return false;
            }
            function tryRotate() {
            const rotated = rotateMatrix(active.shape);
            const next = {...active, shape: rotated};
            // basic wall kicks
            if (!collide(board, next)) { active = next; return; }
            if (!collide(board, {...next, x: next.x-1})) { active = {...next, x: next.x-1}; return; }
            if (!collide(board, {...next, x: next.x+1})) { active = {...next, x: next.x+1}; return; }
            }

            function update(t) {
            if (!running) return;
            const dt = t - (lastTime || t);
            lastTime = t;
            dropCounter += dt;

            // draw
            ctx.clearRect(0,0,width,height);
            drawBackground(t);
            drawBoard();
            drawPiece(active);

            if (dropCounter > dropInterval) {
                dropCounter = 0;
                if (!tryMove(0,1)) {
                merge(board, active);
                clearLines();
                spawnPiece();
                }
            }
            raf = requestAnimationFrame(update);
            }

            function bindControls() {
            if (controlsBound) return; controlsBound = true;
            window.addEventListener('keydown', onKeyDown, { passive: false });
            }
            function unbindControls() {
            if (!controlsBound) return; controlsBound = false;
            window.removeEventListener('keydown', onKeyDown, { passive: false });
            }
            function onKeyDown(e) {
            // do not handle when typing in inputs
            if (isTypingContext(document.activeElement)) return;
            if (!running) return;
            switch (e.code) {
                case 'ArrowLeft': e.preventDefault(); tryMove(-1,0); break;
                case 'ArrowRight': e.preventDefault(); tryMove(1,0); break;
                case 'ArrowDown': e.preventDefault(); tryMove(0,1); break;
                case 'ArrowUp': e.preventDefault(); tryRotate(); break;
                default: break;
            }
            }

            function bindResize() {
            if (resizeBound) return; resizeBound = true;
            window.addEventListener('resize', resizeCanvas);
            }
            function unbindResize() {
            if (!resizeBound) return; resizeBound = false;
            window.removeEventListener('resize', resizeCanvas);
            }

            function startRegattaTetris() {
            if (running) return;
            running = true;
            canvas = document.getElementById('ee-canvas');
            if (!canvas) return;
            ctx = canvas.getContext('2d');
            resizeCanvas();
            resetBoard();
            spawnPiece();
            dropInterval = 650; dropCounter = 0; lastTime = 0;
            bindControls();
            bindResize();
            raf = requestAnimationFrame(update);
            }
            function stopRegattaTetris() {
            running = false;
            if (raf) cancelAnimationFrame(raf);
            unbindControls();
            unbindResize();
            if (canvas) {
                ctx && ctx.clearRect(0,0,canvas.width,canvas.height);
            }
            }
        })();
    </script>

    @push('scripts')
        <script>
            // Toggle user modal
            document.addEventListener('DOMContentLoaded', function() {
                const profileBtn = document.getElementById('btnOpenProfile');
                const userModal = document.getElementById('user-modal');
                const closeBtn = document.getElementById('close-modal');

                if (profileBtn && userModal) {
                    profileBtn.addEventListener('click', function() {
                        userModal.classList.remove('hidden');
                    });
                }

                if (closeBtn && userModal) {
                    closeBtn.addEventListener('click', function() {
                        userModal.classList.add('hidden');
                    });
                }

                if (userModal) {
                    // Close modal when clicking outside
                    userModal.addEventListener('click', function(event) {
                        if (event.target === this) {
                            this.classList.add('hidden');
                        }
                    });

                    // Close modal with Escape key
                    document.addEventListener('keydown', function(event) {
                        if (event.key === 'Escape' && !userModal.classList.contains('hidden')) {
                            userModal.classList.add('hidden');
                        }
                    });
                }
            });
        </script>
    @endpush
    @stack('scripts')
</body>
</html>