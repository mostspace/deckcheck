<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | DeckCheck</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#f7fdf0',
                            100: '#eefbe0',
                            200: '#ddf7c1',
                            300: '#c5f096',
                            400: '#a8e465',
                            500: '#B8EC27',
                            600: '#a6d41e',
                            700: '#8bb018',
                            800: '#6f8a1a',
                            900: '#5a6f1a',
                            950: '#2f3a0a',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .theme-light {
            --bg-primary: #f8f9fb;
            --bg-card: #ffffff;
            --text-primary: #000000;
            --text-secondary: #6b7280;
            --text-muted: #9ca3af;
            --border-color: #e5e7eb;
            --input-bg: #ffffff;
            --input-border: #d1d5db;
        }
        
        .theme-dark {
            --bg-primary: #1f2937;
            --bg-card: #111827;
            --text-primary: #ffffff;
            --text-secondary: #d1d5db;
            --text-muted: #9ca3af;
            --border-color: #374151;
            --input-bg: #374151;
            --input-border: #4b5563;
        }
        
        .theme-transition {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }
    </style>
</head>
<body class="h-full theme-light theme-transition" style="background-color: var(--bg-primary);">
    <div class="min-h-full flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Theme Toggle -->
            <div class="flex justify-end">
                <button id="theme-toggle" class="p-2 rounded-lg border" style="background-color: var(--bg-card); border-color: var(--border-color); color: var(--text-primary);">
                    <i id="theme-icon" class="fas fa-moon"></i>
                </button>
            </div>

            <!-- Logo and Branding -->
            <div class="text-center">
                <div class="flex justify-center mb-6">
                    <div class="w-16 h-16 bg-black rounded-full flex items-center justify-center">
                        <span class="text-white text-2xl font-bold">D</span>
                    </div>
                </div>
                <h1 class="text-3xl font-bold" style="color: var(--text-primary);">DeckCheck</h1>
                <h2 class="mt-6 text-2xl font-bold" style="color: var(--text-primary);">Welcome back!</h2>
                <p class="mt-2 text-sm" style="color: var(--text-secondary);">Your assistant in yacht adventures.</p>
            </div>

            <!-- Login Form -->
            <div class="rounded-lg p-8" style="background-color: var(--bg-card); border: 1px solid var(--border-color);">
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Email</label>
                        <input id="email" name="email" type="email" required autofocus 
                               value="alicesmith@gmail.com"
                               class="w-full px-3 py-2 rounded-lg border text-sm"
                               style="background-color: var(--input-bg); border-color: var(--input-border); color: var(--text-primary);">
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Password</label>
                        <div class="relative">
                            <input id="password" name="password" type="password" required 
                                   value="123456qwerty"
                                   class="w-full px-3 py-2 pr-10 rounded-lg border text-sm"
                                   style="background-color: var(--input-bg); border-color: var(--input-border); color: var(--text-primary);">
                            <button type="button" id="toggle-password" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <i class="fas fa-eye text-sm" style="color: var(--text-muted);"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Forgot Password -->
                    <div class="flex justify-end">
                        <div class="text-sm">
                            <span style="color: var(--text-muted);">Forgot password?</span>
                            <a href="{{ route('password.request') }}" class="ml-1 text-primary-500 underline">Reset</a>
                        </div>
                    </div>

                    <!-- Login Button -->
                    <div>
                        <button type="submit" id="login-button" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-lg text-sm font-medium text-white bg-primary-500 hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                            Log in
                        </button>
                    </div>

                    <!-- Sign Up Link -->
                    <div class="text-center text-sm">
                        <span style="color: var(--text-muted);">Don't have an account?</span>
                        <a href="{{ route('register') }}" class="ml-1 text-primary-500 underline">Sign up</a>
                    </div>

                    <!-- Divider -->
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t" style="border-color: var(--border-color);"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span style="color: var(--text-muted); background-color: var(--bg-card); padding: 0 1rem;">Or</span>
                        </div>
                    </div>

                    <!-- Google Login Button -->
                    <div>
                        <button type="button" class="w-full flex justify-center items-center py-2 px-4 border rounded-lg text-sm font-medium transition-colors"
                                style="background-color: var(--input-bg); border-color: var(--input-border); color: var(--text-primary);">
                            <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                            </svg>
                            Log in with Google
                        </button>
                    </div>
                </form>
            </div>

            <!-- Footer Links -->
            <div class="text-center text-sm" style="color: var(--text-muted);">
                <a href="#" class="hover:text-primary-500">Help</a>
                <span class="mx-2">•</span>
                <a href="#" class="hover:text-primary-500">Terms of Use</a>
                <span class="mx-2">•</span>
                <a href="#" class="hover:text-primary-500">Privacy Policy</a>
            </div>
        </div>
    </div>

    <script>
        // Theme Toggle Functionality
        const themeToggle = document.getElementById('theme-toggle');
        const themeIcon = document.getElementById('theme-icon');
        const body = document.body;
        const loginButton = document.getElementById('login-button');

        // Check for saved theme preference or default to light
        const currentTheme = localStorage.getItem('theme') || 'light';
        setTheme(currentTheme);

        themeToggle.addEventListener('click', () => {
            const newTheme = body.classList.contains('theme-light') ? 'dark' : 'light';
            setTheme(newTheme);
            localStorage.setItem('theme', newTheme);
        });

        function setTheme(theme) {
            if (theme === 'dark') {
                body.classList.remove('theme-light');
                body.classList.add('theme-dark');
                themeIcon.classList.remove('fa-moon');
                themeIcon.classList.add('fa-sun');
                loginButton.textContent = 'Next step';
            } else {
                body.classList.remove('theme-dark');
                body.classList.add('theme-light');
                themeIcon.classList.remove('fa-sun');
                themeIcon.classList.add('fa-moon');
                loginButton.textContent = 'Log in';
            }
        }

        // Password Toggle Functionality
        const togglePassword = document.getElementById('toggle-password');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', () => {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            const icon = togglePassword.querySelector('i');
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>
