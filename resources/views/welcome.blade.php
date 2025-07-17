<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Welcome to DeckCheck</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Tailwind CSS & config -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            navy: {
              50:  '#f0f4f8',
              100: '#d9e2ec',
              200: '#bcccdc',
              300: '#9fb3c8',
              400: '#829ab1',
              500: '#627d98',
              600: '#486581',
              700: '#334e68',
              800: '#243b53',
              900: '#102a43',
            },
          },
          fontFamily: {
            sans: ['Inter','sans-serif'],
          },
        },
      },
    };
  </script>

  <!-- Font Awesome -->
  <script> window.FontAwesomeConfig = { autoReplaceSvg: 'nest' }; </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"
          crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <!-- Typed.js -->
  <script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12/lib/typed.min.js"></script>

  <!-- Inter font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;500;600;700;800;900&display=swap"
        rel="stylesheet">

  <style>
    body { font-family: 'Inter', sans-serif !important; }
    .fa, .fas, .far, .fal, .fab {
      font-family:"Font Awesome 6 Free","Font Awesome 6 Brands" !important;
    }
    ::-webkit-scrollbar { display: none; }
    html, body { -ms-overflow-style: none; scrollbar-width: none; }

    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(20px); }
      to   { opacity: 1; transform: translateY(0);       }
    }
  </style>
</head>

<body class="bg-navy-900 text-white font-sans min-h-screen">

  <!-- HEADER -->
  <header class="py-6 px-6 md:px-10 flex justify-between items-center">
    <div id="logo" class="text-navy-100 font-semibold text-xl flex items-center opacity-0">
      <i class="fa-solid fa-clipboard-check mr-2"></i>
      DeckCheck
    </div>
    <nav>
      <a href="#" class="text-navy-100 hover:text-white transition-colors">
        <i class="fa-solid fa-circle-question"></i>
        <span class="sr-only">Help</span>
      </a>
    </nav>
  </header>

  <!-- MAIN -->
  <main class="px-6 md:px-10 pt-12 pb-20 max-w-3xl mx-auto">
    <!-- HERO -->
    <section class="h-[500px] flex flex-col justify-center">
      <div class="mb-16">
        <!-- Reserve space, then overlay typed text with teal-highlight -->
        <h1 class="text-4xl md:text-5xl font-bold mb-4 relative">
          <span class="invisible"><strong class="text-[#8cdfd8]">DeckCheck</strong> is in Beta.</span>
          <span id="typed-headline" class="absolute left-0 top-0"></span>
        </h1>

        <p id="subheading" class="text-xl md:text-2xl text-navy-200 mb-10 leading-relaxed opacity-0">
          A smarter way to manage maintenance, tasks, and inspections onboard. Built for crew who need to stay ahead of the work.
        </p>

        <div id="cta-buttons" class="flex flex-col sm:flex-row gap-4 mb-12 opacity-0">
          <a href="{{ route('login') }}"
             class="bg-transparent border border-[#8cdfd8] hover:border-teal-500 text-white py-3 px-6 rounded-md font-medium text-center transition-colors">
            Log In
          </a>
          <a href="#"
             class="bg-transparent border border-[#8cdfd8] hover:border-teal-500 text-white py-3 px-6 rounded-md font-medium text-center transition-colors">
            Contact Us
          </a>
        </div>

        <p id="supporting-text" class="text-navy-300 max-w-2xl opacity-0">
          We're actively building DeckCheck with input from working crew. If you're interested in testing or learning more, drop us a line — we'd love to connect.
        </p>
      </div>

      <div id="illustration" class="flex justify-center mt-8 opacity-0">
        <div class="grid grid-cols-3 gap-6">
          <div class="bg-navy-800 p-6 rounded-lg flex flex-col items-center">
            <i class="fa-solid fa-wrench text-navy-400 text-3xl mb-4"></i>
            <span class="text-navy-300 text-sm">Equipment Tracking</span>
          </div>
          <div class="bg-navy-800 p-6 rounded-lg flex flex-col items-center">
            <i class="fa-solid fa-list-check text-navy-400 text-3xl mb-4"></i>
            <span class="text-navy-300 text-sm">Task Management</span>
          </div>
          <div class="bg-navy-800 p-6 rounded-lg flex flex-col items-center">
            <i class="fa-solid fa-compass text-navy-400 text-3xl mb-4"></i>
            <span class="text-navy-300 text-sm">Compliance Tools</span>
          </div>
        </div>
      </div>
    </section>

    <!-- FEATURES -->
    <section id="features-section" class="mt-20 border-t border-navy-700 pt-12 opacity-0">
      <h2 class="text-2xl font-semibold mb-8">Built for professional yacht crew</h2>
      <div class="grid md:grid-cols-2 gap-8">
        <div class="bg-navy-800 p-6 rounded-lg">
          <div class="flex items-center mb-4">
            <i class="fa-solid fa-calendar-check text-navy-400 mr-3"></i>
            <h3 class="font-medium">Maintenance Scheduling</h3>
          </div>
          <p class="text-navy-300 text-sm">
            Track and manage equipment inspection intervals and maintenance schedules in one centralized system.
          </p>
        </div>
        <div class="bg-navy-800 p-6 rounded-lg">
          <div class="flex items-center mb-4">
            <i class="fa-solid fa-clipboard-list text-navy-400 mr-3"></i>
            <h3 class="font-medium">Task Assignment</h3>
          </div>
          <p class="text-navy-300 text-sm">
            Assign tasks to crew members and track completion status with clear accountability.
          </p>
        </div>
        <div class="bg-navy-800 p-6 rounded-lg">
          <div class="flex items-center mb-4">
            <i class="fa-solid fa-file-lines text-navy-400 mr-3"></i>
            <h3 class="font-medium">Compliance Reporting</h3>
          </div>
          <p class="text-navy-300 text-sm">
            Generate reports for flag state, class society, and internal compliance requirements.
          </p>
        </div>
        <div class="bg-navy-800 p-6 rounded-lg">
          <div class="flex items-center mb-4">
            <i class="fa-solid fa-boxes-stacked text-navy-400 mr-3"></i>
            <h3 class="font-medium">Equipment Inventory</h3>
          </div>
          <p class="text-navy-300 text-sm">
            Maintain a comprehensive inventory of onboard equipment with location mapping.
          </p>
        </div>
      </div>
    </section>
  </main>

  <!-- FOOTER -->
  <footer class="bg-navy-800 py-8 px-6 md:px-10 mt-12">
    <div class="max-w-6xl mx-auto">
      <div class="flex flex-col md:flex-row justify-between items-center">
        <div class="mb-6 md:mb-0">
          <div class="text-navy-100 font-semibold flex items-center">
            <i class="fa-solid fa-clipboard-check mr-2"></i>
            DeckCheck
          </div>
          <p class="text-navy-400 text-sm mt-2">Beta version 1.0.0</p>
        </div>
        <div class="flex space-x-6">
          <a href="#" class="text-navy-300 hover:text-white transition-colors">Privacy</a>
          <a href="#" class="text-navy-300 hover:text-white transition-colors">Terms</a>
          <a href="#" class="text-navy-300 hover:text-white transition-colors">Support</a>
        </div>
      </div>
      <div class="mt-8 pt-6 border-t border-navy-700 text-center text-navy-400 text-sm">
        © {{ now()->year }} DeckCheck. All rights reserved.
      </div>
    </div>
  </footer>

  <!-- ANIMATION & TYPING SCRIPT -->
  <script>
    window.addEventListener('load', function() {
      // 1) Fade in logo
      document.getElementById('logo')
              .style.animation = 'fadeInUp 0.8s ease-out forwards';

      // 2) After 0.4s, fade in all other elements
      setTimeout(() => {
        ['subheading','cta-buttons','supporting-text','illustration','features-section']
          .forEach(id => {
            const el = document.getElementById(id);
            if (el) el.style.animation = 'fadeInUp 0.8s ease-out forwards';
          });
      }, 400);

      // 3) After everything is visible (400 + 800 = 1200ms), start typing with HTML content
      setTimeout(() => {
        if (typeof Typed === 'function') {
          new Typed('#typed-headline', {
            strings: ['<strong class="text-[#8cdfd8]">DeckCheck</strong> is in Beta.'],
            typeSpeed: 60,
            showCursor: false,
            contentType: 'html'
          });
        }
      }, 1200);
    });
  </script>
</body>
</html>
