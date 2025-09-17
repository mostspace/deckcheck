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

<body class="h-full text-base-content">

    @php
      $user = auth()->user();
    @endphp

    <div id="app-layout" class="flex">
        {{-- New Sidebar --}}
        @include('v2.components.navigation.sidebar')

        <div class="flex-1 flex flex-col min-h-screen">
            <main id="main-content" class="flex-1 md:ml-20 p-8 transition-margin duration-300 ease-in-out">
                @include('components.main.notification-button')
                @include('components.main.user-modal')
                @yield('content')
            </main>
        </div>
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