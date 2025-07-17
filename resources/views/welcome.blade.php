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
  <header class="py-4 px-4 sm:py-6 sm:px-6 md:px-10 flex justify-between items-center">
    <div id="logo" class="text-navy-100 font-semibold text-lg sm:text-xl flex items-center opacity-0">
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
  <main class="px-4 sm:px-6 md:px-10 pt-12 pb-16 max-w-xl sm:max-w-2xl lg:max-w-3xl mx-auto">
    <!-- HERO -->
    <section class="flex flex-col justify-center">
      <div class="mb-12 sm:mb-16">
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-3 sm:mb-4 relative">
          <span class="invisible"><strong class="text-[#8cdfd8]">DeckCheck</strong> is in Beta.</span>
          <span id="typed-headline" class="absolute left-0 top-0"></span>
        </h1>

        <p id="subheading" class="text-lg md:text-2xl sm:text-xl text-navy-200 mb-8 leading-relaxed opacity-0">
          A smarter way to manage maintenance, tasks, and inspections onboard. Built for crew who need to stay ahead of the work.
        </p>

        <div id="cta-buttons" class="flex flex-col sm:flex-row gap-4 mb-8 opacity-0">
          <a href="{{ route('login') }}"
             class="sm:w-auto bg-transparent border border-[#8cdfd8] hover:border-teal-500 text-white py-3 px-6 rounded-md font-medium text-center transition-colors">
            Log In
          </a>
          <a href="#"
             class="sm:w-auto bg-transparent border border-[#8cdfd8] hover:border-teal-500 text-white py-3 px-6 rounded-md font-medium text-center transition-colors">
            Contact Us
          </a>
        </div>

        <p id="supporting-text" class="text-base sm:text-lg text-navy-300 opacity-0">
          We're actively building DeckCheck with input from working crew. If you're interested in testing or learning more, drop us a line — we'd love to connect.
        </p>
      </div>

      <div id="illustration" class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 mt-6 opacity-0">
        <div class="bg-navy-800 p-4 sm:p-6 rounded-lg flex flex-col items-center">
          <i class="fa-solid fa-wrench text-navy-400 text-2xl sm:text-3xl mb-3"></i>
          <span class="text-navy-300 text-sm sm:text-base">Equipment Tracking</span>
        </div>
        <div class="bg-navy-800 p-4 sm:p-6 rounded-lg flex flex-col items-center">
          <i class="fa-solid fa-list-check text-navy-400 text-2xl sm:text-3xl mb-3"></i>
          <span class="text-navy-300 text-sm sm:text-base">Task Management</span>
        </div>
        <div class="bg-navy-800 p-4 sm:p-6 rounded-lg flex flex-col items-center">
          <i class="fa-solid fa-compass text-navy-400 text-2xl sm:text-3xl mb-3"></i>
          <span class="text-navy-300 text-sm sm:text-base">Compliance Tools</span>
        </div>
      </div>
    </section>

    <!-- FEATURES -->
    <section id="features-section" class="mt-12 sm:mt-16 border-t border-navy-700 pt-10 sm:pt-12 opacity-0">
      <h2 class="text-xl sm:text-2xl font-semibold mb-6 sm:mb-8">Built for professional yacht crew</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8">
        <div class="bg-navy-800 p-4 sm:p-6 rounded-lg">
          <div class="flex items-center mb-3">
            <i class="fa-solid fa-calendar-check text-navy-400 mr-2"></i>
            <h3 class="font-medium text-base sm:text-lg">Maintenance Scheduling</h3>
          </div>
          <p class="text-navy-300 text-sm sm:text-base">
            Track and manage equipment inspection intervals and maintenance schedules in one centralized system.
          </p>
        </div>
        <div class="bg-navy-800 p-4 sm:p-6 rounded-lg">
          <div class="flex items-center mb-3">
            <i class="fa-solid fa-clipboard-list text-navy-400 mr-2"></i>
            <h3 class="font-medium text-base sm:text-lg">Task Assignment</h3>
          </div>
          <p class="text-navy-300 text-sm sm:text-base">
            Assign tasks to crew members and track completion status with clear accountability.
          </p>
        </div>
        <div class="bg-navy-800 p-4 sm:p-6 rounded-lg">
          <div class="flex items-center mb-3">
            <i class="fa-solid fa-file-lines text-navy-400 mr-2"></i>
            <h3 class="font-medium text-base sm:text-lg">Compliance Reporting</h3>
          </div>
          <p class="text-navy-300 text-sm sm:text-base">
            Generate reports for flag state, class society, and internal compliance requirements.
          </p>
        </div>
        <div class="bg-navy-800 p-4 sm:p-6 rounded-lg">
          <div class="flex items-center mb-3">
            <i class="fa-solid fa-boxes-stacked text-navy-400 mr-2"></i>
            <h3 class="font-medium text-base sm:text-lg">Equipment Inventory</h3>
          </div>
          <p class="text-navy-300 text-sm sm:text-base">
            Maintain a comprehensive inventory of onboard equipment with location mapping.
          </p>
        </div>
      </div>
    </section>
  </main>

  <!-- FOOTER -->
  <footer class="bg-navy-800 py-6 px-4 sm:py-8 sm:px-6 md:px-10 mt-12">
    <div class="max-w-xl sm:max-w-2xl lg:max-w-3xl mx-auto">
      <div class="flex flex-col sm:flex-row justify-between items-center">
        <div class="mb-4 sm:mb-0 text-center sm:text-left">
          <div class="text-navy-100 font-semibold flex items-center justify-center sm:justify-start">
            <i class="fa-solid fa-clipboard-check mr-2"></i>
            DeckCheck
          </div>
          <p class="text-navy-400 text-xs sm:text-sm mt-1">Beta version 0.9.2</p>
        </div>
        <div class="flex space-x-4">
          <a href="#" class="text-navy-300 hover:text-white transition-colors text-sm">Privacy</a>
          <a href="#" class="text-navy-300 hover:text-white transition-colors text-sm">Terms</a>
          <a href="#" class="text-navy-300 hover:text-white transition-colors text-sm">Support</a>
        </div>
      </div>
      <div class="mt-6 pt-4 border-t border-navy-700 text-center text-navy-400 text-xs sm:text-sm">
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
